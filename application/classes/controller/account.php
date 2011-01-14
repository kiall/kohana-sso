<?php

class Controller_Account extends Controller_Template {
	public function action_register()
	{
		$this->template->title = 'Register';
		$this->template->body = View::factory('account/register');
		$this->template->body->errors = array();
		
		$user = ORM::factory('user');

		if ($this->request->method() == 'POST')
		{
			try
			{
				$user->values(arr::extract($_POST['user'], array(
						'username',
						'email',
						'password',
						'first_name',
						'last_name',
						'gender',
						'country',
						'language',
						'timezone',
					)))->save();
			}
			catch (ORM_Validation_Exception $e)
			{
				$this->template->body->errors = $e->errors('account/register');
			}
		}

		$this->template->body->user = $user;
	}
	
	public function action_login()
	{
		if ($this->request->method() == 'POST')
		{
			if (Auth::instance()->login($_POST['username'], $_POST['password']))
			{
				Notices::add('success', 'Login sucessful');
				
				$return_url = Session::instance()->get_once('return_url', FALSE);

				if ($return_url)
				{
					$this->request->redirect($return_url);
				}
				else
				{
					$this->request->redirect(Route::url('profile', array(
						'username' => Auth::instance()->get_user()->username,
					), $this->request->protocol()));
				}
			}
			else
			{
				Notices::add('error', 'Login failed');
			}
		}

		$this->template->title = 'Login';
		
		$this->template->body = View::factory('account/login');
	}

	public function action_logout()
	{
		Auth::instance()->logout();
		
		Notices::add('success', 'Logout sucessful');

		$this->request->redirect(Route::url('account', array(
			'action' => 'login',
		), $this->request->protocol()));
	}

	public function action_profile()
	{
		$user = $this->request->param('username', FALSE);

		if ( ! $user)
			throw new Http_Exception_404 ();

		$user = ORM::factory('user')->where('username', '=', $user)->find();

		if ( ! $user->loaded())
			throw new Http_Exception_404 ();


		$this->template->title = $user->username.'\'s Profile';
		
		$this->template->head = array();
		$this->template->head[] = '<link rel="openid2.provider openid.server" href="'.Route::url('openid', NULL, $this->request->protocol()).'"/>';
		$this->template->head[] = '<meta http-equiv="X-XRDS-Location" content="'.Route::url('openid', array('action' => 'userXrds', 'username' => $user->username), $this->request->protocol()).'" />';
		
		$this->template->body = View::factory('account/profile');
	}
}