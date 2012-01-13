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
	
	protected $_template = 'admin/create';
	
	/**
	 * Returns the form for current view
	 */
	public function form()
	{
		if ( ! $this->form)
		{
			// Create a CSRF token field
			$token = new View_Bootstrap_Form_Field('token', Security::token());
			$token->type('hidden');
			
			$this->form = new View_Bootstrap_ModelForm;
			$this->form->load($this->item);			
			$this->form->add($token);
			$this->form->submit()->label(__('Create :model',
				array(':model' => $this->model())));
			
			if ($this->errors)
			{
				$fields = $this->form->fields();
				
				foreach ($this->errors as $field => $error)
				{
					if ($field = Arr::get($fields, $field))
					{
						$field->error($error);
					}
				}
			}
		}
		
		return $this->form;
	}
	
	/**
	 * @return	string	Page headline
	 */
	public function headline()
	{
		return 'Create a new '.$this->model();
	}
	
}
