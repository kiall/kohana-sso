<?php

class PEAR {
	/**
	 * PEAR style auto-loader
	 * 
	 * @param string $class_name
	 */
	public static function autoload($class_name)
	{
		require_once implode('/', explode('_', $class_name)).EXT;
	}
}