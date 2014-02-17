<?php

namespace Api\MVC\Controller;

use Api\Http\Request;


/**
*
* @author Fayssal Tahtoub <fayssal.tahtoub@gmai.com> 
* @version 0.1
*/
class BaseController {

	/**
	* services
	* @var array
	*/
	private $_services = array();

	/**
	* controller name
	* @var array
	*/
	private $_name = "base";

	public function __construct(){
		$this->_services = array();
	}

	/**
	* the magic setter for services
	*
	* @param string $name
	* @param mixed $value
	*
	* @access public
	*/
	public function __set($name,$value){
		$this->_services[$name] = $value;
	}

	/**
	* the magic getter for services
	*
	* @param string $name
	*
	* @access public
	* @return mixed
	*/
	public function __get($name){
		return $this->_services[$name];
	}

	
	/**
	* Set services
	*
	* @param $services
	* @access public
	*/
	public function setServices($services){
		$this->_services = $services;
	}

	/**
	* Get services
	*
	* @return Services
	* @access public
	*/
	public function getServices(){
		return $this->_services;
	}

	/**
	* Set name
	*
	* @param $name
	* @access public
	*/
	public function setName($name){
		$this->_name = $name;
	}

	/**
	* Get name
	*
	* @return name
	* @access public
	*/
	public function getName(){
		return $this->_name;
	}

	/**
	* Get defined service
	* 
	* @param string service name
	*
	* @return mixed
	* @access public
	*/
	public function get($service){
		$key = "";
		switch($service){
			case "request" : $key = "request";break;
			case "orm.manager" : $key = "persistance_manager";break;
			case "session" : $key = "session";break;
			default : throw new \Exception("service ".$service." not found!!"); break;
		}
		return $this->_services[$key];
	}

	/**
	* Set request
	*
	* @param $request
	* @access public
	*/
	public function setRequest(Request $request){
		$this->_services['request'] = $request;
	}


	/**
	* Get request
	*
	* @return Request
	* @access public
	*/
	public function getRequest(){
		return $this->_services['request'];
	}

	/**
	* render to the appropriate view
	*
	* @param 
	* 
	* @return mixed
	* @access public
	*/
	public function render(){
		
	}

	/**
	* redirect the user 
	*
	* @param mixed
	* 
	* @return mixed
	* @access public
	*/
	public function redirectTo($url){
		return __SITE_DNS__.$url;
	}

}