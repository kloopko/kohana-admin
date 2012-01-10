<?php defined('SYSPATH') or die('No direct script access.');

abstract class View_Admin_Layout extends Kohana_Kostache_Layout {
	
	/**
	 * @var	View_Admin_Breadcrumb
	 */
	protected $_breadcrumb;

	protected $_config;

	/**
	 * @var  string  layout path
	 */
	protected $_layout = 'admin/layout';

	/**
	 * @var	int
	 */
	protected $_redirect_timeout = 3;
	
	/**
	 * @var	string	
	 */
	protected $_redirect_url;
	
	/**
	 * @var	string	Name of the current action
	 */
	public $action;
	
	/**
	 * @var	string	Name of the current controller
	 */
	public $controller;
	
	/**
	 * @var	string	Name of the current model
	 */
	public $model;
	
	/**
	 * @var	string
	 */
	public $title;
	
	/**
	 * Breadcrumb getter
	 * This method will create breadcrumb if none exists
	 *
	 * @return	View_Admin_Breadcrumb
	 */
	public function breadcrumb()
	{
		if ($this->_breadcrumb === NULL)
		{
			$breadcrumb = $this->_breadcrumb = new View_Admin_Breadcrumb;
			
			$breadcrumb->add('root', array(
					'text' 	=> 'Admin',
					'url' 	=> Route::url('admin'),
				));
			
			if ($this->controller)
			{
				$breadcrumb->add('controller', array(
					'text' 	=> ucfirst(Inflector::plural($this->model())),
					'url'	=> Route::url('admin', array(
						'controller' => $this->controller,
					)),
				));
			}
			
			switch ($this->action)
			{
				default:
					
					if ($this->action)
					{
						$breadcrumb->add('action', array(
							'text' 	=> ucfirst($this->action),
							'url'	=> $this->current_url(),
						));
					}
					
				break;
				case 'index' : break;
				case 'create' :
					
					$breadcrumb->add('action', array(
						'text' 	=> 'Create new '.$this->model(),
						'url' 	=> $this->current_url(),
					));
					
				break;
				case 'read' :
				
					$breadcrumb->add('action',array(
						'text' 	=> 'View '.$this->model(),
						'url' 	=> $this->current_url(),
					));
				
				break;
				case 'update' :
				
					$breadcrumb->add('action',array(
						'text' 	=> 'Update '.$this->model(),
						'url' 	=> $this->current_url(),
					));
				
				break;
				case 'delete' :
							
					$breadcrumb->add('action',array(
						'text' 	=> 'Delete '.$this->model(),
						'url' 	=> $this->current_url(),
					));
				
				break;
			}
		}
		
		return $this->_breadcrumb;
	}
	
	/**
	 * Application charset
	 * 
	 * @return	string
	 */
	public function charset()
	{
		return Kohana::$charset;
	}
	
	/**
	 * (Create and) Retrieve admin Config
	 *
	 * @return	Config
	 */
	public function config()
	{
		if ($this->_config === NULL)
		{
			$this->_config = Kohana::$config->load('admin')->get('layout');
		}
		
		return $this->_config;
	}
	
	/**
	 * CSS files to load
	 */
	public function css()
	{
		$css = Arr::path($this->config(), 'css');
		
		return $css;
	}
	
	/**
	 * Get the current Requests' URL
	 */
	public function current_url()
	{
		return Request::current()->url(NULL, TRUE).URL::query();
	}
	
	/**
	 * JS to load before content
	 */
	public function head_js()
	{
		return Arr::path($this->config(), 'head_js');
	}

	/**
	 * Figures out the links to display in header 
	 * based on available CRUD controllers
	 * 
	 * @cached	If Kohana::$caching is enabled (should be disabled in dev env)
	 * @return	array	Links ready for display in header
	 */
	public function controller_links()
	{
		$cache_alias = 'View_Admin::controller_links';
		
		// Retrieve cached records to skip the painful reflection process
		if (Kohana::$caching and $cache = Kohana::cache($cache_alias))
			return $cache;
		
		$folder = $folder = 'classes/controller/admin';
		$paths 	= Arr::flatten(Kohana::list_files($folder));
		
		$classes = array();
		
		foreach ($paths as $file => $path)
		{
			// Clean the suffix to get the class name
			$suffix = str_replace(array($folder,'\\','/'), array('','_','_'), $file);			
			$suffix = pathinfo($suffix, PATHINFO_FILENAME);			
			$suffix = trim(strtolower($suffix), '_ ');
		
			$classname = 'Controller_Admin_'.$suffix;
			
			// Create the Reflection controller class
			$controller = new ReflectionClass($classname);
			
			// Include only controllers which extend the CRUD controller
			if ($controller->isSubclassOf('Controller_Admin_CRUD'))
			{
				$model = Arr::get($controller->getDefaultProperties(), '_model');
				
				// If the model isn't manually defined, use the suffix as default
				if ($model === NULL)
				{
					$model = $suffix;
				}
				
				// Make model name human readable
				$humanized = Inflector::humanize($model);
				$humanized = Inflector::plural($humanized);
				
				$links[] = array(
					'selected' 	=> ($this->controller === $suffix),
					'text' 		=> ucfirst($humanized),
					'url' 		=> Route::url('admin', array('controller' => $suffix)),
				);
			}
		}
		
		// Cache links if caching is enabled
		Kohana::$caching and Kohana::cache($cache_alias, $links);
		
		return $links;
	}
	
	/**
	 * Links to display in the header
	 * 
	 * @return	array
	 */
	public function header_links()
	{
		if ( ! Auth::instance()->logged_in('admin'))
			return FALSE;
		
		$links = array_values($this->controller_links());
		
		return $links;
	}
	
	public function home_link()
	{
		return array(
			'url' 	=> Route::url('admin'),
			'text' 	=> Arr::path(Kohana::$config->load('admin'), 'app.name', 'Admin'),
		);
	}
	
	/**
	 * Returns the current language
	 */
	public function lang()
	{
		return I18n::lang();
	}
	
	/**
	 * Returns all required logout links
	 * 
	 * @return	array
	 */
	public function logout_links()
	{
		if ( ! Auth::instance()->logged_in())
			return FALSE;
		
		$base = Route::url('admin/auth',array(
			'action' 	=> 'logout',
			'token' 	=> Security::token(),
		));
		
		$result = array(
			'logout' 			=> $base,
			'logout_destroy' 	=> $base.'?destroy=1',
			'logout_all' 		=> $base.'?all=1',
		);
		
		return $result;
	}
	
	/**
	 * Get the current models' name in human-readable format
	 * 
	 * @return	string
	 */
	public function model()
	{
		return Inflector::humanize($this->model);
	}
	
	/**
	 * Timeout for META REFRESH redirection
	 * 
	 * @param	int		$seconds
	 * @return	object	$this (set)
	 * @return	string	$seconds (get)
	 */
	public function redirect_timeout($seconds = NULL)
	{
		if ($seconds !== NULL)
		{
			$this->_redirect_timeout = $seconds;
			
			return $this;
		}
		
		return $this->_redirect_timeout;
	}
	
	/**
	 * URL for META REFRESH redirection
	 * This parameter has to be set in order for the META tag to appear
	 *
	 * @param	string	$url
	 * @return	string	$url
	 * @return	[View_Admin_Layout](chainable)
	 */
	public function redirect_url($url = NULL)
	{
		if ($url !== NULL)
		{
			$this->_redirect_url = $url;
			
			return $this;
		}
		
		return $this->_redirect_url;
	}
	
	/**
	 * Page title
	 */
	public function title()
	{
		return $this->title ?: Arr::path($this->config(), 'title.default');
	}
	
}
