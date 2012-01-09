<?php

return array(
	'app' => array(
		'name' => 'kloopko',
	),
	'layout' => array(
		'css'	=>	array(
			array(
				'href'	=> Route::url('admin/media', array('file' => 'bootstrap.css')),
				'media'	=> 'screen',
			),
		),
		
		'head_js' => array(
			array('src' => URL::site('js/jquery.ajaxform.js')),
			array('src' => URL::site('colorpicker/js/colorpicker.js')),
		),
		
		'title' => array(
			'default' => 'Kloopko Admin',
		),
	),
	'media' => array(
		
	),
);