<?php

namespace Api\Database\Persistance;

use Api\Database\QueryProviderFactory;
use \ReflectionClass;
use \Exception;

/**
* 
* @author Fayssal Tahtoub <fayssal.tahtoub@gmail.com>
* 
*/
class  PersistanceManager
{
	
	/**
	*
	* @var QueryProviderFactory
	*/
	private $_database_link;

	public function __construct()
	{
		$this->_database_link = QueryProviderFactory::init();
	}

	public function getStore($entity){

		

		$reflection = $this->getEntityReflection($entity);
		
		$storeClass = $this->invokeMethod($reflection,"getStoreClass");

		if(!class_exists($storeClass))
			throw new Exception("the entity store ".$storeClass." doesn't exists !!");

		$storeEntity = new $storeClass();
		$storeEntity->setDatabaseLink($this->getDatabaseLink());
		$storeEntity->setEntityMetadata($reflection->newInstance()->getEntityMetadata());

		return $storeEntity;
	}

	/**
	* Set _database_link
	*
	* @param $database_link
	* @access public
	*/
	public function setDatabaseLink(QueryProviderFactory $database_link){
		$this->_database_link = $database_link;
	}


	/**
	* Get _database_link
	*
	* @return QueryProviderFactory
	* @access public
	*/
	public function getDatabaseLink(){
		return $this->_database_link;
	}

	/**
	* save the entity passed on parameteres to the data base
	*
	* @param AbstractEntity
	* @access public
	*/
	public function save($entity){
		return $this->getDatabaseLink()->save($entity);
	}

	/**
	* update the entity passed on parameteres in the database
	*
	* @param AbstractEntity
	* @access public
	*/
	public function update($entity){
		return $this->getDatabaseLink()->update($entity);
	}

	/**
	* remove the entity passed on parameteres in the database
	*
	* @param AbstractEntity
	* @access public
	*/
	public function remove($entity){
		return $this->getDatabaseLink()->remove($entity);
	}

	/**
	* create the reflection class for gived Entity Class
	*
	* @param string
	* @return RefletionClass
	*/
	private function getEntityReflection($entityClasss){
		if(!class_exists($entityClasss))
			throw new Exception("the entity ".$entityClasss." doesn't exists !!");
		 
		return new ReflectionClass($entityClasss);
	}

	/**
	* invoke a method from gived reflection class
	*
	* @param ReflectionClass
	* @param string
	* @return mixed
	*/
	private function invokeMethod($reflection,$method){

		$reflectionMthd = $reflection->getMethod($method);
		$entity = $reflection->newInstance();
		return  $reflectionMthd->invoke($entity);
	}
	
}