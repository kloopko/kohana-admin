<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Breadcrumb helper
 */
class View_Bootstrap_Breadcrumb extends View_Bootstrap_Nav {
	
	protected $_template = 'bootstrap/breadcrumb';
	
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
