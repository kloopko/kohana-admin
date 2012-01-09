<?php defined('SYSPATH') or die('No direct script access.');

Route::set('admin/auth', 'admin-auth/<action>(/<token>)')
	->defaults(array(
		'controller'	=> 'admin_auth',
		'action'		=> 'login',
	));

Route::set('admin/media', 'admin-media/<file>', array('file' => '.+'))
	->defaults(array(
		'directory'		=> 'admin',
		'controller'	=> 'media',
		'action'		=> 'get',
	));

Route::set('admin', 'admin(/<controller>(/<action>(/<id>)))')
	->defaults(array(
		'directory' 	=> 'admin',
		'controller' 	=> 'index',
		'action' 		=> 'index',
	));
