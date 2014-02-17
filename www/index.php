<?php

define('DS','/');
define('__SITE_DNS__','http://'.$_SERVER['SERVER_NAME'].'/');
define('__ROOT__',realpath(__DIR__.DS.'..'.DS));
define('__CONFIG__',__ROOT__.DS.'config'.DS.'config.php');

//require_once(__ROOT__.DS.'api/Loader/AutoLoader.php');
/*$loader = AutoLoader::initNamespaces(array(
		'Api' => array('ext' => '.php', 'path' => __ROOT__.DS.'api'),
		'Controller' => array('ext' => '.php', 'path' => __ROOT__.DS.'mvc/Controllers'),
		'Model' => array('ext' => '.php', 'path' => __ROOT__.DS.'mvc/Models'),
		'View' => array('ext' => '.php', 'path' => __ROOT__.DS.'mvc/Views'),
	)
);*/

require_once(__ROOT__.'/config/autoload.php');

//ini_set('session.auto_start', 1);


use \Api\Kernel\SysKernel;

$kernel = new SysKernel();
$response = $kernel->prepare();
$kernel->render($response);





