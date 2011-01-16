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
				// Create the user
				$user->create_user($_POST['user'], array(
					'username',
					'email',
					'password',
					'first_name',
					'last_name',
					'gender',
					'country',
					'language',
					'timezone',
				));

				$user->add('roles', ORM::factory('role', 'login')->find());

				// Log the user in..
				Auth::instance()->login($_POST['user']['username'], $_POST['user']['password']);

				// Add a success notice and redirect.
				Notices::add('success', 'Registration sucessful');

				$this->_redirect('profile');
			}
			catch (ORM_Validation_Exception $e)
			{
				Notices::add('error', 'There was a problem with your registration!');
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
				
				$this->_redirect('profile');
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

		$this->_redirect('login');
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
		$this->template->body = View::factory('account/profile');
		$this->template->body->errors = array();

		if ($this->request->method() == 'POST')
		{
			try
			{
				$user->update_user($_POST['user'], array(
					'email',
					'password',
					'first_name',
					'last_name',
					'gender',
					'country',
					'language',
					'timezone',
				));
			}
			catch (ORM_Validation_Exception $e)
			{
				$this->template->body->errors = $e->errors('account/register');
			}
		}

		$this->template->body->user = $user;
	}

	protected function _redirect($to)
	{
		$return_url = Session::instance()->get_once('return_url', FALSE);

		if ($return_url)
		{
			$this->request->redirect($return_url);
		}
		else
		{
			switch ($to)
			{
				case 'profile':
				default:
					$this->request->redirect(Route::url('profile', array(
						'username' => Auth::instance()->get_user()->username,
					), $this->request->protocol()));
				case 'login':
					$this->request->redirect(Route::url('account', array(
						'action' => 'login',
					), $this->request->protocol()));
			}
		}
	}
}