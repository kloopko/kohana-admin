<?php defined('SYSPATH') or die('No direct script access.');
/** 
 * Generic (R)EAD view model - Multiple records
 */
class View_Admin_Index extends View_Admin_Layout {

	/**
	 * Alias for the options column (helps separate it from the rest)
	 */
	const OPTIONS_ALIAS = 'options::alias';
	
	/**
	 * @var	array	Field names to create table columns out of
	 */
	protected $_includables = array('name','title','email');
		
	/**
	 * @var	array	Mustache template
	 */
	protected $_template = 'admin/index';

	/**
	 * @var	Database_Result
	 */
	public $items;
	
	/**
	 * @var	Pagination
	 */
	public $pagination;
	
	/**
	 * @return	array	Create URL and button text
	 */
	public function create_button()
	{
		return array(
			'url' => Route::url('admin', array(
				'controller' 	=> $this->controller,
				'action'		=> 'create',
			)),
			'text' => 'Create a new '.$this->model(),
		);
	}
	
	/**
	 * @var	mixed	cache for self::columns()
	 */
	protected $_columns;
	
	public function columns()
	{
		if ($this->_columns !== NULL)
			return $this->_columns;
		
		// Create an empty model to get info from
		$model = ORM::factory($this->model);
		
		$columns 	= $model->table_columns();		
		$labels 	= $model->labels();
		
		$result 	= array(
			// Always include the primary key
			 array(
				'alias' => $model->primary_key(),
				'name' 	=> 'ID',
			),
		);
		
		// Also include some default columns - if they exist
		foreach ($this->_includables as $includable)
		{
			if (isset($columns[$includable]))
			{
				$label = Arr::get($labels, $includable, $includable);
				
				$result[] = array(
					'alias' => $includable,
					'name' 	=> ucfirst($label),
				);
			}
		}
		
		// Include the created column - if it exists
		if ($created = $model->created_column())
		{
			$result[] = array(
				'type'	=> 'created_column',
				'alias' => $created['column'],
				'name' 	=> 'Created',
			);
		}
		
		// Include the updated column - if it exists
		if ($updated = $model->updated_column())
		{
			$result[] = array(
				'type'	=> 'updated_column',
				'alias' => $updated['column'],
				'name' 	=> 'Last update',
			);
		}
		
		// Append the options array the last
		$result[] = array(
			'alias' => static::OPTIONS_ALIAS,
			'name'	=> 'Options',
		);
		
		return $this->_columns = $result;
	}
	
	/**
	 * Action URL for deleting multiple records
	 * @return	string
	 */
	public function deletemultiple()
	{
		return array(
			'text' => 'Delete selected '.Inflector::plural($this->model()),
			'url' => Route::url('admin', array(
				'controller' 	=> $this->controller,
				'action'		=> 'deletemultiple',
			)),
		);
	}
	
	/**
	 * @return	string	Page headline
	 */
	public function headline()
	{
		return ucfirst(Inflector::plural($this->model()));
	}
	
	/**
	 * List of available actions to display for each individual row
	 *
	 * @return	array
	 */
	public function options()
	{
		return array(
			'read' => array(
				'class' 	=> 'btn primary',
				'text' 		=> 'View',
			),
			'update' => array(
				'class' 	=> 'btn success',
				'text' 		=> 'Edit',
			),
			'delete' => array(
				'class' 	=> 'btn danger',
				'text' 		=> 'Delete',
			),
		);
	}
	
	/**
	 * @var	mixed	local cache for self::results()
	 */
	protected $_result;
	
	/**
	 * Resultset (table body rows)
	 * 
	 * @return	array	(empty if no results)
	 */
	public function result()
	{
		if ($this->_result !== NULL)
			return $this->_result;
		
		$result = array();
		
		if (count($this->items) > 0)
		{
			$result['rows'] = array();
			
			foreach ($this->items as $item)
			{
				// Extract aliased values from self::columns()
				$aliases 	= Arr::pluck($this->columns(), 'alias');
				$extracted 	= Arr::extract($item->as_array(), $aliases);
				
				// Remove the options aliased column
				unset($extracted[static::OPTIONS_ALIAS]);
				
				$values = array_values($extracted);
				
				// Map all values to array('value' => $value)
				$values = array_map(function($val) { 
					
					return array('value' => $val);
				
				}, $values);
				
				
				// Map options
				$options = array();
				
				foreach ($this->options() as $action => $details)
				{
					$options[] = array(
						'class' => $details['class'],
						'text' 	=> $details['text'],
						'url'	=> Route::url('admin', array(
							'controller' 	=> $this->controller,
							'action'		=> $action,
							'id'			=> $item->id,
						)),
					);
				}
				
				// Push data to the rows array
				$result['rows'][] = array(
					'item'		=> $item,
					'options' 	=> $options,
					'values' 	=> $values,
				);
			}
		}
		
		return $this->_result = $result;
	}
	
}
