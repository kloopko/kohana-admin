<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Breadcrumb helper
 */
class View_Admin_Breadcrumb extends Kostache {

	protected $_links = array();
	
	public function add($alias, array $data)
	{
		$this->_links[$alias] = $data;
		
		return $this;
	}
	
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
	
	public function delete($alias)
	{
		unset($this->_links[$alias]);
		
		return $this;
	}
	
}
