<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Breadcrumb helper
 */
class View_Admin_Breadcrumb extends Kostache {

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
	
	/**
	 * Returns links in format suitable for presentation (Mustache)
	 * 
	 * @return	array
	 */
	public function links()
	{
		$result = array();
		$total = count($this->_links);
		
		$i = 0;
		
		foreach ($this->_links as $link)
		{
			$i++;
			
			$link['active'] = ($i === $total);
			
			$result[] = $link;
		}
		
		return $result;
	}
	
}
