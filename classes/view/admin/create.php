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
				
				$this->form = Formo::form()
					->orm('load', $this->item, $excluded, TRUE)
					->set('view_prefix', 'bootstrap')
					->add('token','hidden',Security::token())
					->add('Finish','button','update',array('attr' => 
						array('class' => 'btn large primary','type' => 'submit')));
						
				if ($this->errors)
				{
					foreach ($this->errors as $field => $error)
					{
						if ($field = $this->form->$field)
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
		return 'Create new '.$this->model();
	}
	
}
