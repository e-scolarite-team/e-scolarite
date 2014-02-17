<?php

namespace Api\MVC\View;

use Api\Http\Session\HttpSession;


/**
*
* @author Fayssal Tahtoub <fayssal.tahtoub@gmai.com> 
* @version 0.1
*/
abstract class AbstractView {

	/**
	*
	* @var array
	*/
	private $_datas = array();
	protected $_session;


	/**
	* the magic setter for datas
	*
	* @param string $name
	* @param mixed $value
	*
	* @access public
	*/
	public function __set($name,$value){
		$this->_datas[$name] = $value;
	}

	/**
	* the magic getter for datas
	*
	* @param string $name
	*
	* @access public
	* @return mixed
	*/
	public function __get($name){
		return $this->_datas[$name];
	}

	/**
	* isset
	*
	* @param string $name
	*
	* @access public
	* @return bool
	*/
	public function __isset($name){
		return $this->_datas[$name] ;
	}

	/**
	* unset
	*
	* @param string $name
	*
	* @access public
	*/
	public function __unset($name){
		unset($this->_datas[$name]);
	}

	/**
	* Get session
	*
	* 
	*
	* @access public
	*/
	public function getSession(){
		return $this->_session;
	}

	/**
	* Set session
	*
	* 
	*
	* @access public
	*/
	public function setSession(HttpSession $session){
		$this->_session = $session;
	}

	/**
	*
	*
	*
	*/
	public abstract function render();

}