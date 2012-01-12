<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Admin_Index extends Controller_Admin {

	public function action_index()
	{
		$model = ORM::factory('post', 4);
	
		$form = new View_Bootstrap_ModelForm;	
		
		$form->load($model);
		
		$this->view->form = $form;
		
		$form->load(ORM::factory('user', 1));
	}

}
