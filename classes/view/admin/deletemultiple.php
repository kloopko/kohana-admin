<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Generic (D)ELETE view model - for multiple records
 */
class View_Admin_DeleteMultiple extends View_Admin_Layout {
	
	protected $_template = 'admin/deletemultiple';

	/**
	 * @var	Database_Result
	 */
	public $items;
	
	/**
	 * @return	int	Count of currently selected methods
	 */
	public function count()
	{
		return count($this->items);
	}
	
	/**
	 * @return	string	Page headline
	 */
	public function headline()
	{
		return 'Confirm '.Inflector::plural($this->model()).' deletion';
	}
	
	public function values()
	{
		return array(
			'token' => Security::token()
		);
	}
	
}
