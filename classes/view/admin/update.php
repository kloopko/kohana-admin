<?php defined('SYSPATH') or die('No direct script access.');
/** 
 * Generic (U)PDATE view model - for single record
 */
class View_Admin_Update extends View_Admin_Layout {

	/**
	 * @var	Model
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
		return 'Edit '.$this->model().' #'.$this->item->id;
	}
	
}
