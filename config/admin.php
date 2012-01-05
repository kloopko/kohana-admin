<?php

return array(
	'layout' => array(
		'css'	=>	array(
			array(
				'href'	=> URL::site('admin/bootstrap.css'),
				'media'	=> 'screen',
			),
			array(
				'href' => URL::site('colorpicker/css/colorpicker.css'),
				'media'=> 'screen',
			),
		),
		
		'head_js' => array(
			array('src' => URL::site('js/jquery.js')),
			array('src' => URL::site('js/jquery.ajaxform.js')),
			array('src' => URL::site('colorpicker/js/colorpicker.js')),
		),
		
		'title' => array(
			'default' => 'Kloopko Admin',
		),
	),
);