<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Generic (C)REATE view model - for single record
 */
class View_Admin_Create extends View_Admin_Layout {

	/**
	 * @var	mixed	[Kostache|Formo] form
	 */
	public $form;

	/**
	 * @var	ORM		model
	 */
	public $item;
	
	/**
	 * @var	array	validation errors
	 */
	public $errors;
	
	/**
	 * Returns the form for current view
	 */
	public function form()
	{
		if ( ! $this->form)
		{
			// If Formo is enabled, use it to generate a form
			if (class_exists('Formo'))
			{
				$this->form = Formo::form()->orm('load', $this->item);
			}
		}
		
		return $this->form;
	}
	
	/**
	 * @return	string	Page headline
	 */
	public function headline()
	{
		return 'Create new '.$this->model();
	}
	
}
