<?php 

/**
 * @version 0.1
 * @author Fayssal tahtoub
 */
 class AutoLoader
 {	
 	/**
 	* 
 	* @var array
 	*/
 	public static $_extentions = array();

 	/**
 	* 
 	* @var array
 	*/
 	public static $_include_paths = array();

 	/**
 	* 
 	* @var array
 	*/
 	public static $_namespaces = array();

 	public static $_instance = null;
 	

 	
 	private function __construct()
 	{
 		spl_autoload_register(array($this,'load_class'));
 	}

 	/**
 	*
 	* @param array array('controller' => array('ext' => '.php' , 'path' => ''))
 	*/
 	public static function initNamespaces($class_paths = array()){

 		foreach ($class_paths as  $namespace => $data) {
 			self::$_namespaces[] = $namespace;
 			self::$_extentions[] = $data['ext'];
 			self::$_include_paths[] = $data['path'];
 		}

 		if(self::$_instance == null){
 			self::$_instance = new AutoLoader();
 		}

 		return self::$_instance;
 	}


 	/**
 	*
 	* @param string 
 	* 
 	* @access private
 	*/
 	private function load_class($class){
 		echo "im call".$class;
 		set_include_path(implode(":", self::$_include_paths));
 		//set_include_path('../../api/:'.get_include_path());
 		spl_autoload_extensions(implode(", ", self::$_extentions));
 		//spl_autoload_extensions(self::$_extentions[0]);

 		spl_autoload($class);
 	}


 } 
