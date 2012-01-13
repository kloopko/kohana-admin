<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Basic bootstrap-specific form field
 * 
 * @author	Kemal Delalic	<kemal.delalic@gmail.com>
 */
class View_Bootstrap_Form_Field {

	/**
	 * @var	array	Field attributes which are not specified directly
	 */
	protected $_attrs = array();
	
	/**
	 * @var	string	Field validation error text, if any
	 */
	protected $_error;

	/**
	 * @var	string	Field label text
	 */
	protected $_label;
	
	/**
	 * @var	string	Field name
	 */
	protected $_name;
	
	/**
	 * @var	array	Used only in case the field type is select or radio
	 */
	protected $_options = array();
	
	/**
	 * @var	View_Bootstrap_Form_Field
	 */
	protected $_submit;
	
	/**
	 * @var	string	Field type
	 */
	protected $_type;
	
	/**
	 * @var	mixed	Field value
	 */
	protected $_value;
	
	
	public function __construct($name, $value = NULL, array $attrs = array())
	{
		$this->_name = $name;
		
		$this->_value = $value;
		
		$attrs += array(
			'id' => $name,
		);
		
		$this->attrs($attrs);
	}
	
	public function __call($name, array $args)
	{
		$count = count($args);
		
		if ($count === 1)
		{
			$this->_attrs[$name] = array_shift($args);
			
			return $this;
		}
		elseif ($count > 1)
		{
			throw new Kohana_Exception('Unknown method called: :name',
				array(':name' => $name));
		}
		
		return $this->_attrs[$name];
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
	
	public function attr($key, $value = NULL)
	{
		if ($value === NULL)
			return $this->_attrs[$key];
		
		$this->_attrs[$key] = $value;
		
		return $this;
	}
	
	public function attrs(array $attrs = NULL)
	{
		if ($attrs === NULL)
		{
			return $this->_attrs;
		}
	
		$this->_attrs = $attrs;
		
		return $this;
	}
	
	public function error($error = NULL)
	{
		if ($error === NULL)
			return $this->_error;
		
		// Always capitalize the error sentence
		$this->_error = ucfirst($error);
			
		return $this;
	}
	
	public function label($label = NULL)
	{
		if ($label === NULL)
			return $this->_label;
			
		// Always capitalize the label name
		$this->_label = ucfirst($label);
		
		return $this;
	}
	
	public function name($name = NULL)
	{
		if ($name === NULL)
			return $this->_name;
			
		$this->_name = $name;
		
		return $this;
	}
	
	public function options(array $options = NULL)
	{
		if ($options === NULL)
			return $this->_options;
			
		$this->_options = $options;
		
		return $this;
	}
	
	public function type($type = NULL)
	{
		if ($type === NULL)
			return $this->_type;
			
		$this->_type = $type;
		
		return $this;
	}
	
	public function values(array $values)
	{
		foreach ($values as $field => $value)
		{
			if (array_key_exists($field, $this->_fields))
			{
				$this->_fields->value($value);
			}
		}
		
		return $this;
	}
	
	public function value($value = NULL)
	{
		if ($value === NULL)
			return $this->_value;
			
		$this->_value = $value;
		
		return $this;
	}
	
	/**
	 * Renders the HTML for current form
	 * 
	 * @return	string	HTML
	 */
	public function render()
	{
		return View::factory('bootstrap/form/field', array('field' => $this))
			->render();
	}

}
