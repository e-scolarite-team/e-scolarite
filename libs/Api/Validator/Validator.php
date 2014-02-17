<?php

namespace Api\Validator;


/**
*
* @author Fayssal Tahtoub <fayssal.tahtoub@gmai.com> 
* @version 0.1
*/
class Validator {

	/**
	*
	* @var Validator
	*/
	private static $_instance;

	/**
	* Initializing the Validator with singleton
	* 
	* @access public
	*/
	public static function init(){

		if(self::$_instance == null)
			self::$_instance = new Validator();

		return self::$_instance;
	}
	
	/**
	* Initializing the Validator with singleton and validate the data
	* @param array $haystack array(array('type' => 'email', 'value' => $val, 'message' => 'error message'))
	* @access public
	*
	* @return mixed
	*/
	public static function validate($haystack){

		$instance = self::init();

		$messages = array();

		while(list($key,$element) = each($haystack)){
			$return = $instance->validateElement($element);
			if(is_string($return))
				$messages[] = $return;
		}

		return $messages;
	}

	public function validateElement($element){

		switch ($element['type']) {
			case 'notEmpty':
				return $this->notEmpty($element);
				break;

			case 'email':
				return $this->isEmail($element);
				break;

			case 'float':
				return $this->isFloat($element);
				break;
			
			default:
				throw new \Exception("Validator ".$element['type']." not exists!!", 1);
				break;
		}
	}

	private function notEmpty($element){
		if(isset($element['value']) && !empty($element['value']))
			return true;
		return $element['message'];
	}

	private function isEmail($element){
		if(isset($element['value']) && !empty($element['value'] && filter_var($element['value'], FILTER_VALIDATE_EMAIL)))
			return true;
		return $element['message'];
	}

	private function isFloat($element){
		if(isset($element['value']) && !empty($element['value'] && filter_var($element['value'], FILTER_VALIDATE_FLOAT)))
			return true;
		return $element['message'];
	}
}