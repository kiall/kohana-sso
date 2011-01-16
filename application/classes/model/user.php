<?php defined('SYSPATH') or die('No direct access allowed.');

class Model_User extends Model_Auth_User {

	public function rules()
	{
		$rules = parent::rules();

		$rules += array(
			'first_name' => array(
				array('not_empty'),
				array('min_length', array(':value', 2)),
				array('max_length', array(':value', 255)),
			),
			'last_name' => array(
				array('not_empty'),
				array('min_length', array(':value', 2)),
				array('max_length', array(':value', 255)),
			),
			'gender' => array(
				array('in_array', array(':value', array('M', 'F'))),
			),
			'country' => array(
				array('array_key_exists', array(':value', (array) Kohana::config('countries'))),
			),
			'language' => array(
				array('array_key_exists', array(':value', (array) Kohana::config('languages'))),
			),
			'timezone' => array(
				array('in_array', array(':value', (array) Kohana::config('timezones'))),
			),
		);

		return $rules;
	}
} // End User Model