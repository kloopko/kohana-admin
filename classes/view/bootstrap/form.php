<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Basic bootstrap-specific form helper
 * 
 * @author	Kemal Delalic	<kemal.delalic@gmail.com>
 */
class View_Bootstrap_Form {

	/**
	 * @var	string	form action
	 */
	protected $_action;
	
	/**
	 * @var	array	form attributes
	 */
	protected $_attrs = array();

	/**
	 * @var	array	fields
	 */
	protected $_fields = array();
	
	/**
	 * @var	string	method
	 */
	protected $_method = 'POST';
	
	/**
	 * Creates an empty form object
	 *
	 * @param	string	$action
	 * @param	array	$attrs
	 */
	public function __construct($action = '', array $attrs = NULL)
	{
		$this->_action = $action;
		
		$this->attrs($attrs);
		
		// Create a default submit button
		$this->_submit = new View_Bootstrap_Form_Field('submit','1');
		$this->_submit->type('button')
			->attr('class','btn large primary')
			->label('Submit');
	}
	
	/**
	 * Magic method for casting the object to string
	 *
	 * @return	string
	 */
	public function __toString()
	{
		try
		{
			return $this->render();
		}
		catch (Exception $e)
		{
			ob_start();
			
			Kohana_Exception::handler($e);
			
			return (string) ob_get_clean();
		}
	}
	
	/**
	 * Form action getter / setter
	 * @param	string	$action
	 * @return	View_Bootstrap_Form (chainable on set)
	 * @return	string	action
	 */
	public function action($action = NULL)
	{
		if ($action !== NULL)
		{
			$this->_action = $action;
			
			return $this;
		}
		
		return $this->_action;
	}
	
	/**
	 * Add a field
	 * @param	View_Bootstrap_Form_Field	$field
	 * @return	View_Bootstrap_Form		chainable
	 */
	public function add(View_Bootstrap_Form_Field $field)
	{
		$this->_fields[$field->name()] = $field;
		
		return $this;
	}
	
	/**
	 * Attributes setter / getter
	 * If an array is passed, *all* current attributes are replaced
	 * 
	 * @param	array	$attrs
	 * @return	View_Bootstrap_Field (chainable on set)
	 * @return	array	$attrs
	 */
	public function attrs(array $attrs = NULL)
	{
		if ($attrs === NULL)
		{
			return $this->_attrs;
		}
	
		$this->_attrs = $attrs;
		
		return $this;
	}
	
	/**
	 * Fields getter
	 * 
	 * @return	array
	 */
	public function fields()
	{
		return $this->_fields;
	}
	
	/**
	 * Form method setter / getter
	 * @param	method	
	 * @return	View_Bootstrap_Form (chainable on set)
	 * @return	string	method
	 */
	public function method($method = NULL)
	{
		if ($method !== NULL)
		{
			$this->_method = $method;
			
			return $this;
		}
		
		return $this->_method;
	}
	
	/**
	 * Renders the HTML for current form
	 * 
	 * @return	string	HTML
	 */
	public function render()
	{
		$view = View::factory('bootstrap/form', array('form' => $this));
		
		return $view->render();
	}
	
	/**
	 * Removes a field from the form
	 * In case array is passed, fields will be removed recursively
	 *
	 * @param	mixed	$name string or array of strings
	 * @return	View_Bootstrap_Form (chainable)
	 */
	public function remove($name)
	{
		if (is_array($name))
		{
			foreach ($name as $single)
			{
				$this->remove($single);
			}
		}
		else
		{
			if (isset($this->_fields[$name]))
			{
				unset($this->_fields[$name]);
			}
		}
		
		return $this;
	}
	
	/**
	 * Submit field getter / setter
	 * 
	 * @param	View_Bootstrap_Form_Field	$field
	 * @return	[View_Bootstrap_Form] (chainable on set)
	 * @return	View_Bootstrap_Form_Field	
	 */
	public function submit(View_Bootstrap_Form_Field $submit = NULL)
	{
		if ($submit === NULL)
			return $this->_submit;
			
		$this->_submit = $submit;
		
		return $this;
	}
	
	/**
	 * Unloads all the fields values and errors
	 * 
	 * @return	[View_Bootstrap_Form]
	 */
	public function unload()
	{
		foreach ($this->_field as $field)
		{
			$field->error(FALSE)
				->value(FALSE);
		}
		
		return $this;
	}

}
