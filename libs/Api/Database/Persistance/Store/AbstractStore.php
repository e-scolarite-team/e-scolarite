<?php

namespace Api\Database\Persistance\Store;

use Api\Database\QueryProviderFactory;

/**
* @author Fayssal Tahtoub <fayssal.tahtoub@gmail.com>
* @version 0.1
*/
abstract class AbstractStore
{
	
	/**
	*
	* @var QueryProviderFactory
	*/
	private $_database_link;

	/**
	*
	* @var array
	*/
	private $_entity_metadata;

	public function __construct()
	{
		
	}

	public abstract function getEntityName();

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
	* Set _entity_metadata
	*
	* @param array $entity_metadata
	* @access public
	*/
	public function setEntityMetadata($entity_metadata){
		$this->_entity_metadata = $entity_metadata;
	}


	/**
	* Get _entity_metadata
	*
	* @return array
	* @access public
	*/
	public function getEntityMetadata(){
		return $this->_entity_metadata;
	}

	/**
	* find all entity
	*
	* @return array
	*/
	public function findAll(){
		return $this->getDatabaseLink()->findAll($this->getEntityMetadata());
	}

	/**
	* find all entity with gived parameters
	*
	* @return array
	*/
	public function findAllBy($conditions=array()){
		return $this->getDatabaseLink()->findAllBy($this->getEntityMetadata(),$conditions);
	}
	
	/**
	* find an entity by primary key
	*
	* @return array
	*/
	public function findById($id){
		return $this->getDatabaseLink()->findById($this->getEntityMetadata(),$id);
	}

	/**
	* execute a user query
	*
	* @return array
	*/
	public function executeQuery($query,$as = \PDO::FETCH_OBJ){
		return $this->getDatabaseLink()->executeQuery($query,$as);
	}

	/**
	* execute a non query query insert|update|delete
	*
	* @return array
	*/
	public function executeNonQuery($query){
		return $this->getDatabaseLink()->executeNonQuery($query);
	}

	/**
	* execute a scalar query 
	*
	* @return array
	*/
	public function executeScalar($query){
		return $this->getDatabaseLink()->executeScalar($query);
	}
}