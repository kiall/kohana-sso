<?php

require_once "Auth/OpenID.php";
require_once "Auth/OpenID/Server.php";
require_once "Auth/OpenID/SReg.php";

class Controller_OpenID extends Controller {

	protected $store;
	protected $server;

	public function before()
	{
		parent::before();

//		$this->store = new Auth_OpenID_OpenIDStore_Kohana_ORM();
		$this->store = new Auth_OpenID_FileStore('/tmp/openid5/');
		$this->server = new Auth_OpenID_Server($this->store, Route::url('openid'));
	}

	public function action_index()
	{
		$this->response->headers('X-XRDS-Location', Route::url('openid', array('action' => 'idpXrds')));

		$request = $this->server->decodeRequest();

		if ( ! $request)
		{
			$request = Session::instance()->get('request');
			
			if ( ! $request)
			{
				// Maybe force a login here?
				$this->response->body(View::factory('openid/about'));
				return;
			}

			$request = unserialize($request);
		}

		Session::instance()->set('request', serialize($request));

		if (in_array($request->mode, array('checkid_immediate', 'checkid_setup')))
		{
			if ($request->idSelect())
			{
				// Perform IDP-driven identifier selection
				if ($request->mode == 'checkid_immediate')
				{
					$response = $request->answer(FALSE);
				}
				else
				{
					$this->response->body(View::factory('openid/trust', array(
						'request' => $request,
					)));

					return;
				}
			}
			else if ($request->immediate)
			{
				$response = $request->answer(FALSE, Route::url('openid'));
			}
			else
			{
				if (!Auth::instance()->logged_in())
				{
					Session::instance()->set('return_url', $this->request->url());
					$this->request->redirect(Route::url('account', array('action' => 'login')));
				}



				$this->response->body(View::factory('openid/trust', array(
					'request' => $request,
				)));

				return;
			}
		}
		else
		{
			$response = $this->server->handleRequest($request);
		}

		$webresponse = $this->server->encodeResponse($response);

		if ($webresponse->code != AUTH_OPENID_HTTP_OK)
		{
			$this->response->status($webresponse->code);
		}

		foreach ($webresponse->headers as $k => $v)
		{
			$this->response->headers($k, $v);
		}

		$this->response->body($webresponse->body);
	}

	public function action_trust()
	{
		$request = unserialize(Session::instance()->get('request'));

		$trusted = isset($_POST['trust']);

		$fail_cancels = TRUE;

		if ( ! $request)
		{
			Session::instance()->set('request', NULL);

			$this->request->redirect();
		}

		$user = FALSE;

		if (Auth::instance()->logged_in())
		{
			$user = Auth::instance()->get_user();
		}

		$idpSelect = '';

		if ($request->idSelect())
		{
			if ($idpSelect)
			{
				$req_url = Route::url('account', array('action' => 'profile', 'username' => @$_POST['idSelect']));
			}
			else
			{
				$trusted = FALSE;
			}
		}
		else
		{
			$req_url = $request->identity;
		}

		$req_url = Route::url('account', array('action' => 'profile', 'username' => $user->username));

		Session::instance()->set('request', serialize($request));

		if ( ! $request->idSelect() && ($req_url != Route::url('account', array('action' => 'profile', 'username' => $user->username))))
		{
			$this->request->redirect(Route::url('account', array('action' => 'login')));
		}

		$trust_root = $request->trust_root;

		if ($trusted)
		{
			Session::instance()->set('request', NULL);

			$response = $request->answer(TRUE, NULL, $req_url);

			$sreg_data = array(
			   'fullname' => $user->first_name.' '.$user->last_name,
			   'nickname' => $user->username,
			   'email'    => $user->email,
			   'gender'   => $user->gender,
			   'country'  => $user->country,
			   'language' => $user->language,
			   'timezone' => $user->timezone,
			);

			// Add the simple registration response values to the OpenID
			// response message.
			$sreg_request = Auth_OpenID_SRegRequest::fromOpenIDRequest($request);

			$sreg_response = Auth_OpenID_SRegResponse::extractResponse($sreg_request, $sreg_data);

			$sreg_response->toMessage($response->fields);

			// Generate a response to send to the user agent.
			$webresponse = $this->server->encodeResponse($response);

			foreach ($webresponse->headers as $k => $v)
			{
				$this->response->headers($k, $v);
			}

			$this->auto_render = FALSE;

			$this->response->body($webresponse->body);
		}
		elseif ($fail_cancels)
		{
			Session::instance()->set('request', serialize($request));

			$this->request->redirect($request->getCancelURL());
		}
		else
		{
			$this->response->body('TrustRender');
			return;
			return trust_render($request);
		}
	}

	public function action_idpXrds()
	{
		$this->auto_render = FALSE;

		$this->response->headers('Content-type', 'application/xrds+xml');

		$this->response->body('<?xml version="1.0" encoding="UTF-8"?>
<xrds:XRDS
xmlns:xrds="xri://$xrds"
xmlns="xri://$xrd*($v*2.0)">
<XRD>
<Service priority="0">
<Type>'.Auth_OpenID_TYPE_2_0_IDP.'</Type>
<URI>'.Route::url('openid').'</URI>
</Service>
</XRD>
</xrds:XRDS>');
	}

	public function action_userXrds()
	{
		$this->auto_render = FALSE;

		$this->response->headers('Content-type', 'application/xrds+xml');

		$this->response->body('<?xml version="1.0" encoding="UTF-8"?>
<xrds:XRDS
xmlns:xrds="xri://$xrds"
xmlns="xri://$xrd*($v*2.0)">
<XRD>
<Service priority="0">
<Type>'.Auth_OpenID_TYPE_2_0.'</Type>
<Type>'.Auth_OpenID_TYPE_1_1.'</Type>
<URI>'.Route::url('openid').'</URI>
</Service>
</XRD>
</xrds:XRDS>');
	}

}