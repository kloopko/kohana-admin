<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Admin_Auth extends Controller_Admin {

	public function action_login()
	{
		// Redirect logged-in admins to the administration index
		// All users which make it to the action are considered admins
		if (Auth::instance()->logged_in())
			$this->request->redirect(Route::url('admin'));
		
		if ($this->request->method() === Request::POST)
		{
			$validation = Validation::factory($this->request->post())
				->rule('username','not_empty')
				->rule('password','not_empty')
				->rule('token','not_empty')
				->rule('token','Security::check');
				
			if ($validation->check())
			{
				list($username, $password, $remember) = array_values(Arr::extract(
					$this->request->post(), array(
						'username','password','remember',
					)));
				
				if (Auth::instance()->login($username, $password, $remember))
				{
					$this->request->redirect(Route::url('admin'));
				}
				
				$this->view->errors = array(
					'username' => __('Invalid username or password')
				);
			}
			else
			{
				$this->view->errors = $validation->errors('validation');
			}
			
			$this->view->values = $this->request->post();
		}
	}

	public function action_logout()
	{
		// Log out only if the token is ok
		if (Security::token() === $this->request->param('token'))
		{
			Auth::instance()->logout();
		}
		
		$this->request->redirect(Route::url('admin/auth'));
	}

}
