<?php

abstract class Controller_Template extends Kohana_Controller_Template {
	public function after()
	{
		if ($this->auto_render)
		{
			View::set_global('user', Auth::instance()->get_user());

			// This breaks the userguide :/
			$this->template->menu = View::factory('menu');
		}

		return parent::after();
	}
}