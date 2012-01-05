<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Generic (C)REATE view model - for single record
 */
class View_Admin_Create extends View_Admin_Layout {

	/**
	 * @var	ORM		model
	 */
	public $item;
	
	/**
	 * @var	array	validation errors
	 */
	public $errors;
	
	/**
	 * @return	string	Page headline
	 */
	public function headline()
	{
		return 'Create new '.$this->model();
	}
	
}
