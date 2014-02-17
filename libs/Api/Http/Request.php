<?php

namespace Api\Http;

use Api\Reader\ConfigReader;

/**
* @author Fayssal tahtoub
* @version 0.1
*/
class Request
{
	/**
	*
	* @var Request
	*/
	private static $_instance;

	private $query = null;

	private $post = null;

	/**
	*
	* @var array
	*/
	private $_data = array();



	private function __construct()
	{
		$this->query = &$_GET;
		$this->post = &$_POST;
	}

	/**
	* Initializing the Request with singleton
	* 
	* @access public
	*/
	public static function create(){
		if(self::$_instance == null)
			self::$_instance = new Request();

		return self::$_instance;
	}

	public function getRoute(){
		return $this->query['route'];
	}

	public function getQuery($key){
		return $this->query[$key];
	}

	public function getPost($key){
		return $this->post[$key];
	}

	/**
	* test if request is post or not
	* @access public
	*
	* @return bool
	*/
	public function isPost(){
		return  (strtolower($_SERVER['REQUEST_METHOD']) == 'post'); 
	} 

	


}