<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Provides basic admin layout
 * 
 * @author	Kemal Delalic	<kemal.delalic@gmail.com>
 */
abstract class View_Admin_Layout extends Kohana_Kostache_Layout {
	
	/**
	 * @var	array
	 */
	protected $_config;

	/**
	 * @var	string  layout path
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
	 * @var	View_Bootstrap_Breadcrumb
	 */
	public $breadcrumb;
	
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
	 * This method will create default breadcrumb if one hasn't been defined 
	 * already
	 *
	 * @param	View_Bootstrap_Breadcrumb	$breadcrumb to inject
	 * @return	View_Admin_Layout			In case breadcrumb is injected (chaining)
	 * @return	View_Bootstrap_Breadcrumb
	 */
	public function breadcrumb(View_Bootstrap_Breadcrumb $breadcrumb = NULL)
	{
		if ($breadcrumb !== NULL)
		{
			$this->breadcrumb = $breadcrumb;
			
			return $this;
		}
		
		// If breadcrumb isn't set, use the default
		if ($this->breadcrumb === NULL)
		{
			$this->breadcrumb = new View_Admin_Layout_Breadcrumb;
			$this->breadcrumb
				->setup($this->controller, $this->model())
				->setup_action($this->action, $this->current_url());
		}
		
		return $this->breadcrumb;
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
	 *
	 * @return	array
	 */
	public function css()
	{
		$css = Arr::path($this->config(), 'css');
		
		return $css;
	}
	
	/**
	 * Get the current Requests' URL
	 * 
	 * @return	string
	 */
	public function current_url()
	{
		return Request::current()->url(NULL, TRUE).URL::query();
	}
	
	/**
	 * JS to load before content
	 *
	 * @return	array
	 */
	public function head_js()
	{
		return Arr::path($this->config(), 'head_js');
	}
	
	/**
	 * @var	View_Admin_Layout_ControllerNav		Local cache
	 */
	protected $_controller_menu;
	
	/**
	 * Links to display in the header
	 * 
	 * @return	array
	 */
	public function header_links()
	{
		if ( ! Auth::instance()->logged_in('admin'))
			return FALSE;
		
		// Load the controller menu only once
		if ($this->_controller_menu === NULL)
		{
			$this->_controller_menu = new View_Admin_Layout_ControllerNav;
			$this->_controller_menu->load_folder('controller/admin');
		}
		
		return $this->_controller_menu;
	}
	
	/**
	 * The main link to admin homepage
	 * 
	 * @return	array
	 */
	public function home_link()
	{
		return array(
			'url' 	=> Route::url('admin'),
			'text' 	=> Arr::path(Kohana::$config->load('admin'), 'app.name', 'Admin'),
		);
	}
	
	/**
	 * Returns the current language
	 * 
	 * @return	string
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
	 * Page <title>
	 *
	 * @return	string
	 */
	public function title()
	{
		return $this->title ?: Arr::path($this->config(), 'title.default');
	}
	
}
