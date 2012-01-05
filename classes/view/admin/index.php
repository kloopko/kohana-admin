<?php defined('SYSPATH') or die('No direct script access.');
/** 
 * Generic (R)EAD view model - for multiple records
 */
class View_Admin_Index extends View_Admin_Layout {

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
		$result = array($model->primary_key() => 'ID');
		
		// Also include some default columns - if they exist
		foreach (array('name','title','email') as $includable)
		{
			if (isset($columns[$includable]))
			{
				$result[$includable] = ucfirst($includable);
			}
		}
		
		// Include the created column - if it exists
		if ($created = $model->created_column())
		{
			$result[$created['column']] = 'Created';
		}
		
		// Include the updated column - if it exists
		if ($updated = $model->updated_column())
		{
			$result[$updated['column']] = 'Last update';
		}
		
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
				$push = $item->as_array();
				
				$push['delete_url'] = Route::url('admin', array(
					'controller' 	=> $this->controller,
					'action'		=> 'delete',
					'id'			=> $item->id,
				));
				
				$push['update_url'] = Route::url('admin', array(
					'controller' 	=> $this->controller,
					'action'		=> 'update',
					'id'			=> $item->id,
				));
				
				$push['view_url']	= Route::url('admin', array(
					'controller'	=> $this->controller,
					'action'		=> 'view',
					'id'			=> $item->id,
				));
				
				$result['rows'][] = $push;
			}
		}
		
		return $this->_result = $result;
	}
	
}
