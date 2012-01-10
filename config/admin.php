<?php

return array(
	/* application (admin module) configuration */
	'app' => array(
		/* name of the application */
		'name' => 'kloopko',
	),
	/* layout configuration */
	'layout' => array(
		/* CSS files to load in page <head> */
		'css'	=>	array(
			array(
				'href'	=> Route::url('admin/media', array('file' => 'bootstrap.css')),
				'media'	=> 'screen',
			),
		),
		
		/* JavaScript files to load in page <head> */
		'head_js' => array(
			array('src' => 'https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js'),
			array('src' => Route::url('admin/media', array('file' => 'bootstrap-dropdown.js'))),
		),
		
		/* default page <title>*/
		'title' => array(
			'default' => 'Kloopko Admin',
		),
	),
);
