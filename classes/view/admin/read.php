<?php defined('SYSPATH') or die('No direct script access.');
/** 
 * Generic (R)EAD view model - for single record
 */
class View_Admin_Read extends View_Admin_Layout {
	
	protected $_template = 'admin/read';

	/**
	 * @var	Model
	 */
	public $item;
	
	/**
	 * @return	array	Available buttons
	 */
	public function buttons()
	{
		return array(
			array(
				'class' => 'large success',
				'text' => 'Update',
				'url' => Route::url('admin', array(
					'controller' 	=> $this->controller,
					'action' 		=> 'update',
					'id' 			=> $this->item->id,
				)),
			),
			array(
				'class' => 'large error',
				'text' => 'Delete',
				'url' => Route::url('admin', array(
					'controller' 	=> $this->controller,
					'action' 		=> 'delete',
					'id' 			=> $this->item->id,
				)),
			),
		);
	}
	
	/**
	 * @return	string	Page headline
	 */
	public function headline()
	{
		return ucfirst($this->model()).' #'.$this->item->id;
	}
	
	/**
	 * @return	array	field => value
	 */
	public function values()
	{
		$array 	= $this->item->object();
		$labels = $this->item->labels();
		
		$result = array();
		
		foreach ($array as $field => $value)
		{
			$result[] = array(
				'label' => ucfirst(Arr::get($labels, $field, $field)),
				'value' => $value,
			);
		}
		
		return $result;
	}
	
}
