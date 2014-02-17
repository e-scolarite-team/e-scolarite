<?php

namespace Api\Http\Session;

use Api\Utils\StringUtils;

/**
* @author fayssal tahtoub
* @version 0.1
*/
class HttpSession
{
	private $container  = null;

	public function __construct()
	{
		
	}

	/**
	* start the session
	* 
	* @access public
	*/
	public function start(){
		if(!$this->isStart()){
			session_start();
			$this->container = &$_SESSION;
			//var_dump("expression");
			//$this->container = array();
		}
		if(session_id() == '')
			throw new \Exception("Can't start session!!", 1);
			
	}

	/**
	* shutdown the session
	* 
	* @access public
	*/
	public function shutdown(){
		if($this->isStart()){
			session_unset();
			session_destroy();
			$this->container = array();
		}			
	}

	/**
	* start the session
	* @param string
	* @param mixed 
	* @access public
	*/
	public function set($name,$value){

		if(is_object($value)){
			$value = serialize($value);
		}
		$this->container[$name] = $value;
	}

	/**
	* start the session
	* @param string
	* @return mixed
	* @access public
	*/
	public function get($name){
		if($this->has($name)){
			$value = $this->container[$name];

			if(is_string($value) && StringUtils::isSerialized($value))
				$value = unserialize($value);

			return $value;

		}

		return null;

	}

	/**
	* test if the name is registred
	* @param string
	*
	* @access public
	* @return bool
	*/
	public function has($name){
		if($this->isStart() && isset($this->container[$name]))
			return true;
		return false;
	}

	/**
	* remove 
	* @param string
	*
	* @access public
	* @return bool
	*/
	public function remove($name){
		if($this->has($name)){

			unset($this->container[$name]);

			if($this->has($name))
				return false;

			return true;

		}
		return false;
	}

	/**
	* test if the session start
	* 
	*
	* @access public
	* @return bool
	*/
	public function isStart(){
		if(session_status() == PHP_SESSION_ACTIVE)
			return true;
		return false;
	}




}