<?php
	return array(
		'_database' => array(
			'_driver' => 'mysql',
			'_host' => 'localhost',
			'_port' => '3306',
			'_login' => 'root',
			'_password' => '001985',
			'_dbname' => 'e-commerce',
			),

		'upload_dir' => __ROOT__.DS.'www/uploads',

		'upload_dir_name' => 'uploads',

		'_admin_auth' => array(
			'_login' => 'eroot',
			'_password' => '001985'
			),

		'_routes' => array(
			'home' => array(
				'_hook' => 'Index:index',
				'_params' => array(),
				),
			),

		'_controllers' => array(
			'Index' => 'MVC\Controllers\IndexController',
			),

		'_factories' => array(
			'validator' => 'Api\Validator\Validator'
			),

		'_template' => array(
			'index' => 'index/index.phtml',
			'layout' => 'layout/layout.phtml',
			),

		'_template_path' => '../apps/MVC/Views/',

		'_menu_system' => array(
			),
			
	); 
