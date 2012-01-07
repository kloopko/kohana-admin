<?php defined('SYSPATH') or die('No direct script access.');
/** 
 * Generic (R)EAD view model - for single record
 */
class View_Admin_Read extends View_Admin_Layout {

	/**
	 * @var	Model
	 */
	public $item;
	
	/**
	 * @return	string	Page headline
	 */
	public function headline()
	{
		return 'View '.$this->model().' #'.$this->item->id;
	}
	
}
