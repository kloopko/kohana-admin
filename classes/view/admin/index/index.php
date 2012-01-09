<?php defined('SYSPATH') or die('No direct script access.');

class View_Admin_Index_Index extends View_Admin_Layout {

	public function username()
	{
		return Auth::instance()->get_user()->username;
	}

}
