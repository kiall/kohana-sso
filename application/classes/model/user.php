<?php defined('SYSPATH') or die('No direct access allowed.');

class Model_User extends Model_Auth_User {

	public function rules()
	{
		$rules = parent::rules();

		$rules += array(
			'first_name' => array(
				array('not_empty'),
			),
		);

		return $rules;
	}

} // End User Model