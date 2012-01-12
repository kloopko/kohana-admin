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
			// If Formo is enabled, use it to generate a form
			if (class_exists('Formo'))
			{
				// Always exclude the primary key field
				$excluded = array($this->item->primary_key());
				
				// If there is a creation date column, don't display it
				if ($this->item->created_column())
				{
					$excluded[] = Arr::get($this->item->created_column(), 'column');
				}
				
				// If there is a update date column, don't display it
				if ($this->item->updated_column())
				{
					$excluded[] = Arr::get($this->item->updated_column(), 'column');
				}
				
				$this->form = new View_Bootstrap_ModelForm;
				$this->form->load($this->item);
				
				// Add CSRF token field
				$token = new View_Bootstrap_Form_Field('token', Security::token());
				$token->type('hidden');
				
				$this->form->add($token);
				
				/*
				$this->form = Formo::form()
					->orm('load', $this->item, $excluded, TRUE)
					->set('view_prefix', '_bootstrap')
					->add('token', 'hidden', Security::token())
					->add('Finish', 'button','create', array('attr' => 
						array(
							'class' => 'btn large primary',
							'type' 	=> 'submit',
						)
					));
				*/
				if ($this->errors)
				{
					$fields = $this->form->fields();
					
					foreach ($this->errors as $field => $error)
					{
						if (isset($fields[$field]) and $field = $this->form->$field)
						{
							$field->error($error);
						}
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
