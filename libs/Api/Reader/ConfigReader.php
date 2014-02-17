<?php
namespace Api\Reader;

/**
* this class is used to read the configuration file entry
*
* @author Fayssal tahtoub
* @version 0.1
*/
class ConfigReader{

	/**
	*
	* @var array
	*/
	private $_configuration = array();

	/**
	*
	* @var ConfigReader
	*/
	private static $_instance;

	private function __construct($path){
		$this->_configuration = include($path);
	}

	/**
	* Initializing the ConfigReader with singleton
	* @param string
	* @access public
	*/
	public static function init($path = __CONFIG__){

		if(self::$_instance == null)
			self::$_instance = new ConfigReader($path);

		return self::$_instance;
	}

	/**
	* Initializing the ConfigReader with singleton and get the data
	* @param string $key use this structure parent/child/sub-child
	* @access public
	*
	* @return mixed
	*/
	public static function config($key,$search=false){
		$instance = self::init();
		return $instance->get($key,$search);
	}

	/**
	* get a data configuration from an array
	* @param string $key use this structure parent/child/sub-child
	* @param string $haystack the collection of data
	* @access public
	*
	* @return mixed
	*/
	public static function configFrom($key,$search=false,$haystack=array()){
		$instance = self::init();
		return $instance->get($key,$search,$haystack);
	}


	/**
	* Initializing the ConfigReader with singleton
	* @param string $key use this structure parent/child/sub-child
	* @access public
	*
	* @return mixed
	*/
	public function get($key,$search=false,$config = array()){

		$entries = explode('/', $key);
		if(count($config) == 0)
			$config = $this->_configuration;
		$data = $this->find($config,$entries,0,$search);

		return $data;

	}

	/**
	* Initializing the ConfigReader with singleton
	* @param array $config the current config entry
	* @param array $entries the entries key
	* @param integer $idx the index of current key
	* @access private
	*
	* @return mixed
	*/
	private function find($config,$entries,$idx,$search = false){
		if(!array_key_exists($entries[$idx], $config)){
			if($search)
				return false;
			else
			throw new \Exception("Error Processing Config file - key ".$entries[$idx]." not found", 1);
		}
			
		$value  = $config[$entries[$idx]];
		
		if(is_array($value) && ($idx < count($entries) - 1 )){
			$idx++;
			return $this->find($value,$entries,$idx,$search);
		}

		return $value;
	}



}