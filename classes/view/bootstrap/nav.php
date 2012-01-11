<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Reads available controllers using Reflection to create a menu
 * 
 * @author	Kemal Delalic	<kemal.delalic@gmail.com>
 */
class View_Bootstrap_Nav extends Kostache {
	
	/**
	 * @var	string	template file
	 */
	protected $_template = 'bootstrap/nav';
	
	/**
	 * @var	array	navigation links
	 */
	protected $_links = array();
	
	/**
	 * Add a link to the list of breadcrumbs
	 *
	 * @param	string	$alias for the link, for later manipulation
	 * @param	array	$data for the link
	 * @return 	[View_Admin_Breadcrumb] (chainable)
	 */
	public function add($alias, array $data)
	{
		$this->_links[$alias] = $data;
		
		return $this;
	}
	
	/**
	 * Delete an aliased link from the list of breadcrumbs
	 *
	 * @param	string	$alias for the link
	 * @return 	[View_Admin_Breadcrumb] (chainable)
	 */
	public function delete($alias)
	{
		unset($this->_links[$alias]);
		
		return $this;
	}
	
}
