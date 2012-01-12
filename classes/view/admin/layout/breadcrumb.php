<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Reads available controllers using Reflection and creates the menu with submenus
 * 
 * @author	Kemal Delalic	<kemal.delalic@gmail.com>
 */
class View_Admin_Layout_Breadcrumb extends View_Bootstrap_Breadcrumb {
	
	/**
	 * @var	string	controller name
	 */
	public $controller;
	
	/**
	 * @var	string	model name
	 */
	public $model_name;
	
	public function setup($controller, $model_name)
	{
		$this->controller 	= $controller;
		$this->model_name 	= $model_name;
		
		$this->add('root', array(
				'text' 	=> 'Admin',
				'url' 	=> Route::url('admin'),
			));
		
		if ( ! empty($controller))
		{
			$this->add('controller', array(
				'text' 	=> ucfirst(Inflector::plural($model_name)),
				'url'	=> Route::url('admin', array(
					'controller' => $controller,
				)),
			));
		}
		
		return $this;
	}
	
	public function setup_action($action, $url)
	{
		switch ($action)
		{
			default			:
				
				$text = $action ? ucfirst($action) : NULL;
				
			break;
			case 'index' 	:
				
				// Nothing to add
				
			break;
			case 'create' 	:
				
				$text = 'Create a new '.$this->model_name;
				
			break;
			case 'read' 	:
			
				$text = 'View '.$this->model_name;
				
			break;
			case 'update' 	:
			
				$text = 'Update '.$this->model_name;
				
			break;
			case 'delete' 	:
			
				$text = 'Delete '.$this->model_name;
				
			break;
			case 'deletemultiple' :
				
				$text = 'Delete multiple '.Inflector::plural($this->model_name);
			
			break;
		}
			
		if (isset($text))
		{
			$this->add('action', array(
				'text' 	=> $text,
				'url'	=> $url,
			));
		}
		
		return $this;
	}
	
}
