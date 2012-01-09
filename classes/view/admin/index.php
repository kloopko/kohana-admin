<?php defined('SYSPATH') or die('No direct script access.');
/** 
 * Generic (R)EAD view model - Multiple records
 */
class View_Admin_Index extends View_Admin_Layout {

	const OPTIONS_ALIAS = 'options::col';

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
			'text' => 'Create new '.$this->model,
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
		
		$columns = $model->table_columns();
		
		// Always include the primary key first
		$result = array(
			 array(
				'alias' => $model->primary_key(),
				'name' 	=> 'ID',
			),
		);
		
		// Also include some default columns - if they exist
		foreach (array('name','title','email') as $includable)
		{
			if (isset($columns[$includable]))
			{
				$result[] = array(
					'alias' => $includable,
					'name' 	=> ucfirst($includable),
				);
			}
		}
		
		// Include the created column - if it exists
		if ($created = $model->created_column())
		{
			$result[] = array(
				'alias' => $created['column'],
				'name' 	=> 'Created',
			);
		}
		
		// Include the updated column - if it exists
		if ($updated = $model->updated_column())
		{
			$result[] = array(
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
	 * @return	string	Page headline
	 */
	public function headline()
	{
		return ucfirst(Inflector::plural($this->model()));
	}
	
	/**
	 * @var	mixed	local cache for self::results()
	 */
	protected $_result;
	
	/**
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
				$extracted = Arr::extract($item->as_array(), Arr::pluck($this->columns(), 'alias'));
				
				// Remove the options aliased column
				unset($extracted[static::OPTIONS_ALIAS]);
				
				// Create a numeric array for Mustache
				$numeric = array_fill(0, count($extracted), '');
				
				$values = array_combine(array_keys($numeric), $extracted);
				
				// Map all values to array('value' => $value)
				$values = array_map(function($val) { 
					
					return array('value' => $val);
				
				}, $values);
				
				
				// Map options
				$options = array();
				
				foreach (static::options() as $action)
				{
					$details = static::options_array($action);
					
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
					'options' 	=> $options,
					'values' 	=> $values,
				);
			}
		}
		
		return $this->_result = $result;
	}
	
	/**
	 * List of available actions to display for each individual row
	 *
	 * @return	array
	 */
	public static function options()
	{
		return array(
			'read',
			'update',
			'delete',
		);
	}
	
	/**
	 * List of action => details for individual row (action) options
	 * This has to be a method so the child view can override / extend it
	 * 
	 * @param	string	$key - to return individual row
	 * @return	array
	 */
	public static function options_array($key = NULL)
	{
		static $options_array;
		
		if ($options_array === NULL)
		{
			$options_array = array(
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
		
		if ($key !== NULL)
			return $options_array[$key];
		
		return $options_array;
	}
	
}
