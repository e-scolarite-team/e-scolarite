<?php

namespace Api\Database\Persistance\Entity;

/**
* @author fayssal tahtoub
*
*/
abstract class AbstractEntity
{
	
	function __construct()
	{
		
	}

	/**
	* Get the name of class store
	*
	* @return string
	* @access public
	*/
	abstract public function getStoreClass();

	/**
	* Get the metadata of class 
	*
	* @return array
	* @access public
	*/
	abstract public function getEntityMetadata();

	/**
	* set the object propety
	*
	* @param array
	* @access public
	*/
	abstract public function push($data);

	/**
	* set array value from object 
	*
	* @return array
	* @access public
	*/
	abstract public function toArray();
}