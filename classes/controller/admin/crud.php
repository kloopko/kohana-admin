<?php defined('SYSPATH') or die('No direct script access.');

abstract class Controller_Admin_CRUD extends Controller_Admin {

	/**
	 * @var	string		Model name
	 */
	protected $_model;
	
	public function before()
	{
		parent::before();
		
		if ($this->_model === NULL)
		{
			throw new Kohana_Exception('$_model not defined in :controller',
				array(':controller' => $this->request->controller()));
		}
		
		// If there is no action specific view, use the CRUD default
		if ($this->auto_view === TRUE and ! $this->view)
		{
			list ($view_name, $view_path) = static::find_default_view($this->request);
			
			if (Kohana::find_file('classes', $view_path))
			{
				$this->view = new $view_name;
			}
		}
		
		// If view has been detected/specified already, pass required vars to it
		if ($this->view)
		{
			$this->view->action 	= $this->request->action();			
			$this->view->controller = $this->request->controller();			
			$this->view->model 		= $this->_model;
		}
	}

	/**
	 * Action for reading multiple records of the current model
	 * Pagination will be displayed in case there are more records than the page limit
	 */
	public function action_index()
	{
		$count = ORM::factory($this->_model)->count_all();
		
		$pagination = Pagination::factory(array(
			'items_per_page'=> 10,
			'total_items' 	=> $count,
		))->route_params(array(
			'directory' 	=> $this->request->directory(),
			'controller' 	=> $this->request->controller(),
			'action'		=> $this->request->action(),
			'view'			=> 'pagination/bootstrap',
		));
		
		$items = ORM::factory($this->_model)
			->limit($pagination->items_per_page)
			->offset($pagination->offset)
			->find_all();
		
		// Pass to view
		$this->view->items 		= $items;
		$this->view->pagination = $pagination;
	}
	
	/**
	 * Action for creating a single record
	 */
	public function action_create()
	{
		$item = ORM::factory($this->_model);
		
		if ($this->request->method() === Request::POST)
		{
			$validation = Validation::factory($this->request->post())
				->rule('token','not_empty')
				->rule('token','Security::check');
				
			try
			{
				$item->values($this->request->post())
					->create($validation);
					
				$this->request->redirect(Route::url('admin', array(
					'controller' => $this->request->controller()
				)));
			}
			catch (ORM_Validation_Exception $e)
			{
				$this->view->errors = $e->errors('');
			}
		}
			
		$this->view->item = $item;
	}
	
	/**
	 * Action for reading a single record
	 */
	public function action_read()
	{
		$item = ORM::factory($this->_model, $this->request->param('id'));
		
		if ( ! $item->loaded())
		{
			throw new HTTP_Exception_404(':model with ID :id doesn`t exist!',
				array(':model' => $this->_model, ':id' => $this->request->param('id')));
		}
		
		$this->view->item = $item;
	}
	
	/**
	 * Action for updating a single record
	 */
	public function action_update()
	{
		$item = ORM::factory($this->_model, $this->request->param('id'));
		
		if ( ! $item->loaded())
			throw new HTTP_Exception_404('Resource not found');
			
		if ($this->request->method() === Request::POST)
		{
			$validation = Validation::factory($this->request->post())
				->rule('token','not_empty')
				->rule('token','Security::check');
				
			try
			{
				$item->values($this->request->post())
					->update($validation);
					
				$this->request->redirect(Route::url('admin', array(
					'controller' 	=> $this->request->controller(),
					'action'		=> 'read',
					'id'			=> $item->id,
				)));
			}
			catch (ORM_Validation_Exception $e)
			{
				$this->view->errors = $e->errors('validation');
			}
		}
			
		$this->view->item = $item;
	}
	
	/**
	 * Action for deleting a single record
	 */
	public function action_delete()
	{
		$item = ORM::factory($this->_model, $this->request->param('id'));
		
		if ( ! $item->loaded())
		{
			throw new HTTP_Exception_404(ucfirst($this->_model).' doesn`t exist: :id', 
				array(':id' => $this->request->param('id')));
		}
		
		if ($this->request->method() === Request::POST)
		{
			$action = $this->request->post('action');
			
			if ($action !== 'yes')
			{
				$this->request->redirect(Route::url('admin', array(
					'controller' => $this->request->controller()
				)));
			}
			
			$item->delete();
			
			$this->request->redirect(Route::url('admin', array(
				'controller' => $this->request->controller()
			)));
		}
		
		$this->view->item = $item;
	}
	
	/**
	 * Action for deleting multiple records
	 * 
	 * 	ORM::delete() is invoked on each of the records instead of 
	 * 	deleting them all with a single query
	 */
	public function action_deletemultiple()
	{
		$ids = ($this->request->method() === Request::POST)
			? $this->request->post('ids')
			: $this->request->query('ids');
			
		// If no IDs were specified, redirect back to referrer
		if (count($ids) === 0)
		{
			$redirect_url = $this->request->referrer() ?: Route::url('admin', array(
				'controller' => $this->controller()
			));
			
			$this->request->redirect($redirect_url);
		}
		
		// Create an empty instance of current model to get additional infos
		$object = ORM::factory($this->_model);
		
		// Select items requested for deletion
		$items = ORM::factory($this->_model)
			->where($object->object_name().'.'.$object->primary_key(),'IN',$ids)
			->find_all();
		
		if ($this->request->method() === Request::POST)
		{
			foreach ($items as $item)
			{
				$item->delete();
			}
			
			$this->request->redirect(Route::url('admin', array(
				'controller' => $this->request->controller()
			)));
		}
		
		$this->view->items = $items;
	}
	
	/**
	 * Finds the default CRUD view for Request specified (ignores the controller)
	 * 
	 * @param	Request
	 * @return	array	view_name, view_path
	 */
	public static function find_default_view(Request $request)
	{
		// Empty array for view name chunks
		$view_name = array('View');
		
		// If current request's route is set to a directory, prepend to view name
		if ($request->directory())
		{
			array_push($view_name, $request->directory());
		}
		
		// Append controller and action name to the view name array
		$view_name[] = $request->action();
		
		// Merge all parts together to get the class name
		$view_name = implode('_', $view_name);
		
		// Get the path respecting the class naming convention
		$view_path = strtolower(str_replace('_', DIRECTORY_SEPARATOR, $view_name));
		
		return array($view_name, $view_path);
	}
	
}
