<?php defined('SYSPATH') or die('No direct script access.');

abstract class View_Admin_Layout extends Kohana_Kostache_Layout {

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
	 * Application charset
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
	 * Returns the current language
	 */
	public function lang()
	{
		return I18n::lang();
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
