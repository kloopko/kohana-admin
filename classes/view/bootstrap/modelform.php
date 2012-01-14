<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Creates a Bootstrap form using a ORM model
 * 
 * @author	Kemal Delalic	<kemal.delalic@gmail.com>
 */
class View_Bootstrap_ModelForm extends View_Bootstrap_Form {

	/**
	 * @var	ORM 	Current model object
	 */
	protected $_model;
		
	/**
	 * @var	array	Columns to use as name for remote relations
	 */
	protected $_name_columns = array('name','title','username','email');

	/**
	 * @var	string	Model fields are currently loaded for (object_name)
	 */
	protected $_loaded_model;

	/**
	 * Loads a model into the current form object
	 * Fields will be loaded only the first time
	 * 
	 * @param	ORM		$model 
	 * @return	View_Bootstrap_ModelForm (chainable)
	 */
	public function load(ORM $model)
	{
		$this->_model = $model;
		
		if ($this->_loaded_model !== $model->object_name())
		{
			// Load all fields only if they haven't been loaded yet
			$this->load_fields($model);
		}
		else
		{
			// Unload all field values and errors
			$this->load_values($model);
		}
		
		return $this;
	}
	
	/**
	 * Loads the fields for a ORM model
	 * 
	 * @param	ORM		$model
	 * @return	View_Bootstrap_ModelForm	(chainable)
	 * @todo	move view logics where they belong
	 * @todo	make this driver-based for different database types
	 */
	public function load_fields(ORM $model)
	{
		// Clean the fields array
		$this->_fields = array();
		
		$columns 	= $model->list_columns();
		
		$labels 	= $model->labels();
		$rules		= $model->rules();
		
		$belongs_to = $model->belongs_to();
		
		if (count($belongs_to) > 0)
		{
			$belongs_to	= array_combine(Arr::pluck($belongs_to, 'foreign_key'), $belongs_to);
		}
		
		$has_one = $model->has_one();
		
		if (count($has_one) > 0)
		{
			$has_one = array_combine(Arr::pluck($has_one, 'foreign_key'), $has_one);
		}
		
		// Unset primary key, created and update column(s) as
		// they shouldn't be updatable
		if ($primary = $model->primary_key())
		{
			unset($columns[$primary]);
		}
		
		if ($created = $model->created_column())
		{
			unset($columns[$created['column']]);
		}
		
		if ($updated = $model->updated_column())
		{
			unset($columns[$updated['column']]);
		}
		
		foreach ($columns as $name => $column)
		{
			// Create the field
			$field = new View_Bootstrap_Form_Field($name, $model->$name);
			
			// Get the field label, avoiding the Inflector call if possible
			$label = Arr::get($labels, $name, Inflector::humanize($name));			
			$field->label($label);
			
			// Get first param of each field rule
			#$rule_names = Arr::pluck(Arr::get($rules, $name, array()), 0);
			
			// Filter out rules which are useless for field generation
			#$rule_names = array_filter($rule_names, 'is_string');
			
			if (isset($belongs_to[$name]))
			{
				$remote_model = ORM::factory($belongs_to[$name]['model']);
				
				$options = $remote_model
					->find_all()
					->as_array($model->primary_key(), $this->_find_name_column($remote_model));
					
				$field->type('select')
					->options($options);
			}
			elseif (isset($has_one[$name]))
			{
				$remote_model = ORM::factory($has_one[$name]['model']);
				
				$options = $remote_model
					->find_all()
					->as_array($model->primary_key(), $this->_find_name_column($remote_model));
				
				$field->type('select')
					->options($options);
			}
			else
			{
				// This pretty much forces MySQL *only*
				switch ($column['data_type'])
				{
					default:
						
						$field->type('text');
					
					break;
					case 'enum' :
					
						$options = array_combine($column['options'], $column['options']);
					
						$field->type('select')
							->options($options);
					
					break;
					case 'int' :
					
						$field->type('text')
							->attr('class','span4');
					
					break;
					case 'varchar' :
					
						$field->type('text')
							->attr('class','span8');
					
					break;
					case 'text' :
					case 'tinytext' :
						
						$field->type('textarea')
							->attr('class','span8');
						
					break;
				}
			}
			
			$this->add($field);
		}
		
		$this->_loaded_model = $model->object_name();
	}
	
	public function load_values(ORM $model)
	{
		$this->unload();
				
		foreach ($this->_fields as $field)
		{
			$field->value($model->{$field->name});
		}
		
		return $this;
	}
	
	protected function _find_name_column(ORM $model)
	{
		$columns = $model->list_columns();
		
		foreach ($this->_name_columns as $column)
		{
			if (isset($columns[$column]))
				return $column;
		}
		
		return $model->primary_key();
	}
}
