<?php defined('SYSPATH') or die('No direct script access.');

abstract class Controller_Admin_CRUD extends Controller_Admin {

	/**
	 * @var	string		Model name
	 */
	protected $_model;
	
	public function before()
	{
		parent::before();
		
		// Detect the model if not specified
		if ($this->_model === NULL)
		{
			$this->_model = $this->request->controller();
		}
		
		// If there is no model specific view, use the CRUD default
		if ($this->auto_view === TRUE and ! $this->view)
		{
			list ($view_name, $view_path) = Controller_Admin_CRUD::find_view($this->request);
			
			if (Kohana::find_file('classes', $view_path))
			{
				$this->view = new $view_name;
			}
		}
		
		// If view has been detected/specified already, set other defaults
		if ($this->view)
		{
			// Assign current action name to the view
			$this->view->action = $this->request->action();
			
			// Assign current controller name to the view
			$this->view->controller = $this->request->controller();
			
			// Assign current model name to the view
			$this->view->model = $this->_model;
			
			// Check if form partial exists and assign it (for CREATE / UPDATE)
			if (in_array($this->request->action(), array('create','update')))
			{
				$folder = str_replace('_', DIRECTORY_SEPARATOR, $this->_model);
				
				$tpl = "admin/{$folder}/_form";
				
				if ($path = Kohana::find_file('templates', $tpl, 'mustache'))
				{
					$this->view->partial('form', $tpl);
				}
				else
				{
					#echo 'Form not available: '.$tpl;
				}
			}
		}
	}

	public function action_index()
	{
		$count = ORM::factory($this->_model)->count_all();
		
		$pagination = Pagination::factory(array(
			'items_per_page'=> 8,
			'total_items' 	=> $count,
		))->route_params(array(
			'directory' 	=> $this->request->directory(),
			'controller' 	=> $this->request->controller(),
			'action'		=> $this->request->action(),
		));
		
		$items = ORM::factory($this->_model)
			->limit($pagination->items_per_page)
			->offset($pagination->offset)
			->find_all();
		
		// Pass to view
		$this->view->items 		= $items;
		$this->view->pagination = $pagination;
	}
	
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
			
			$this->response->body('SUCCESS');
		}
		
		$this->view->item = $item;
	}
	
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
				$this->view->errors = $e->errors('validation');
			}
		}
			
		$this->view->item = $item;
		$this->view->breadcrumb()->add('action',array(
			'text' 	=> 'Create new '.$this->_model, 
			'url' 	=> $this->request->url(),
		));
	}
	
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
	 * Finds the default CRUD view for Request specified (ignores the controller)
	 * 
	 * @param	Request
	 * @return	array	view_name, view_path
	 */
	public static function find_view(Request $request)
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
