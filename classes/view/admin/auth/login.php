<?php defined('SYSPATH') or die('No direct script access.');
/** 
 * Login view model
 */
class View_Admin_Auth_Login extends View_Admin_Layout {
	
	/**
	 * @var	array
	 */
	public $errors;

	/**
	 * @var	array
	 */
	public $values = array();

	/**
	 * @return	array	Values with CSRF token included
	 */
	public function values()
	{
		return array('token' => Security::token()) + $this->values;
	}
	
}
