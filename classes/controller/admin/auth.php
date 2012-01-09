<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Admin_Auth extends Controller_Admin {

	public function action_login()
	{
		if ($this->request->method() === Request::POST)
		{
			list($username, $password, $remember) = array_values(
				Arr::extract($this->request->post(), array(
					'username','password','remember',
				)));
				
			if (Auth::instance()->login($username, $password, $remember))
			{
				$this->request->redirect(Route::url('admin'));
			}
			
			$this->view->errors = array(
				'username' => __('Invalid username or password')
			);
			
			$this->view->values = $this->request->post();
		}
	}

	public function action_logout()
	{
		if (Security::token() !== $this->request->param('id'))
			$this->request->redirect($this->request->referrer());
			
		Auth::instance()->logout();
		
		$this->request->redirect(Route::url('admin', array(
			'controller' 	=> 'auth',
			'action' 		=> 'login',
		)));
	}

}
