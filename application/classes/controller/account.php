<?php

class Controller_Account extends Controller_Template {
	public function before()
	{
		parent::before();

//		if ( ! Auth::instance()->logged_in() AND $this->request->action() != 'login' AND $this->request->action() != 'profile')
//		{
//			Session::instance()->set('return_url', $this->request->url());
//
//			$this->request->redirect(Route::url('account', array(
//				'action' => 'login',
//			)));
//		}
	}
	
	public function action_index()
	{
		$this->request->redirect(Route::url('account', array(
			'action' => 'login',
		)));
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
					$this->request->redirect(Route::url('account', array(
						'action' => 'profile',
						'username' => Auth::instance()->get_user()->username,
					)));
				}
			}
			else
			{
				Notices::add('error', 'Login failed');
			}
		}

		$this->template->title = 'Login';
		$this->template->breadcrumb = array('Account', 'Login');
		
		$this->template->body = View::factory('account/login');
	}

	public function action_logout()
	{
		Auth::instance()->logout();
		
		Notices::add('success', 'Logout sucessful');

		$this->request->redirect(Route::url('account', array(
			'action' => 'login',
		)));
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
		$this->template->breadcrumb = array('Account', 'Profile');
		$this->template->menu = View::factory('menu');

		$this->template->head = array();
		$this->template->head[] = '<link rel="openid2.provider openid.server" href="'.Route::url('openid').'"/>';
		$this->template->head[] = '<meta http-equiv="X-XRDS-Location" content="'.Route::url('openid', array('action' => 'userXrds', 'username' => $user->username)).'" />';
		
		$this->template->body = View::factory('account/profile', array(
			'user' => $user,
		));
	}
}