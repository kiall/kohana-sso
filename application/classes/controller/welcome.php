<?php

class Controller_Welcome extends Controller {
	public function action_index()
	{
		$this->request->redirect(Route::url('account', array(
			'action' => 'login',
		)));
	}
}