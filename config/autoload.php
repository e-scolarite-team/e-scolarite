<?php
require_once __DIR__.'/../vendor/symfony/class-loader/Symfony/Component/ClassLoader/UniversalClassLoader.php';
 
use Symfony\Component\ClassLoader\UniversalClassLoader;
 



$loader = new UniversalClassLoader();
$loader->registerNamespace('Api' , __ROOT__.'/libs');
$loader->registerNamespace('MVC' , __ROOT__.'/apps');

$loader->register();
