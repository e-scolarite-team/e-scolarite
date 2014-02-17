<?php
namespace Api\Database;

use Api\Reader\ConfigReader;
use Api\Database\Query\QueryGenerator;
use \PDO as PDO;

/**
*
* @author Fayssal tahtoub
* @version 0.1
*/
class QueryProviderFactory
{
	
	/**
	*
	* @var QueryProviderFactory
	*/
	private static $_instance;

	/**
	*
	*
	* @var QueryGenerator
	*/
	private $_generator;

	/**
	*
	*
	* @var \PDO
	*/
	private $handler;

	private function __construct()
	{
		$this->connect();
		$this->_generator = new QueryGenerator();
	}

	/**
	* establish the connection to the database
	* 
	* @access private
	*/
	private function connect(){
		try{

			$this->handler =  new PDO($this->dsn(),ConfigReader::config('_database/_login'),ConfigReader::config('_database/_password'));
			//$this->handler->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		}catch(PDOException $pex){

			echo($pex->getMessage());
		}
		
	}

	/**
	* retrive the dsn from config file
	* 
	* @access private
	*/
	private function dsn(){
		return ConfigReader::config('_database/_driver').':host='.ConfigReader::config('_database/_host').';dbname='.ConfigReader::config('_database/_dbname');
	}

	
	
	/**
	* Initializing the QueryProviderFactory with singleton
	* 
	* @access public
	*/
	public static function init(){

		if(self::$_instance == null)
			self::$_instance = new QueryProviderFactory();

		return self::$_instance;
	}

	/**
	* retrive form database all content of table name as an assosiative table
	* @param string $entity_metadata les informations sur la Classe persitante
	* 
	* 
	* @return array
	*/
	public function findAll($entity_metadata){
		$collections = $this->findAllBy($entity_metadata);
		
		return $collections;
	}

	/**
	* retrive form database all content of table name as an assosiative table
	* @param string $entity_metadata les informations sur la Classe persitante
	* @param array $conditions this is the content of clause where
	* 
	* 
	* @return array
	*/
	public function findAllBy($entity_metadata, $conditions = array()){
		
		$query = $this->getQueryGenerator()->generateSelect($entity_metadata['_table_name'],$conditions);
		//var_dump($query);
		//var_dump($conditions);
		$stm = $this->handler->prepare($query);

		$stm->setFetchMode(PDO::FETCH_CLASS,$entity_metadata['_class_name']);

		foreach ($conditions as $key => $value) {
			$stm->bindValue(':'.$key,$value);
		}

		$stm->execute();
		return $stm->fetchAll();
	}

	/**
	* retrive one line from table_name
	* @param array $entity_metadata 
	* @param mixed $id
	* 
	* @return AbstractEntity
	*/
	public function findById($entity_metadata,$id){
		$primaryKey = $this->getEntityPrimaryKey($entity_metadata);
		$conditions = array($primaryKey["column_name"] => $id);
		$query = $this->getQueryGenerator()->generateSelect($entity_metadata['_table_name'],$conditions);
		
		$stm = $this->handler->prepare($query);

		$stm->setFetchMode(PDO::FETCH_CLASS,$entity_metadata['_class_name']);

		foreach ($conditions as $key => $value) {
			$stm->bindValue(':'.$key,$value);
		}

		$stm->execute();
		return $stm->fetch();
	}


	/**
	* execute a select user query
	* @param string $query the user query
	* @param boolean $as_object si true le result doit etre return sous form objet
	* 
	* @return array
	*/
	public function executeQuery($query,$as = PDO::FETCH_ASSOC){
		//var_dump($query);
		$statment = $this->handler->prepare($query);
		$statment->setFetchMode($as);

		$statment->execute();
		return $statment->fetchAll();
	}

	/**
	* execute a customize update|insert|delete 
	* @param string $query the scalar user query
	* @param boolean $as_object si true le result doit etre return sous form objet
	* 
	* @return integer
	*/
	public function executeNonQuery($query){
		//var_dump($query);
		$statment = $this->handler->prepare($query);

		$statment->execute();
		return $statment->rowCount();
	}

	/**
	* execute a scalar query 
	* @param string $query the scalar user query
	* @param boolean $as_object si true le result doit etre return sous form objet
	* 
	* @return mixed
	*/
	public function executeScalar($query){
		//var_dump($query);
		$statment = $this->handler->prepare($query);
		$statment->setFetchMode(PDO::FETCH_BOTH);

		$statment->execute();
		return $statment->fetchColumn();
	}

	/**
	* save the entity to do database
	*
	* @param AbstractEntity
	* @access public
	*/
	public function save($entity){

		$query = $this->getQueryGenerator()->generateInsert($entity->getEntityMetadata());
		//var_dump($query);
		$statment = $this->handler->prepare($query);
		$this->bindValsFromMeta($statment,$entity->getEntityMetadata(),'_is_auto');

		$statment->execute();
		return $statment->rowCount();

	}

	/**
	* save the entity to do database
	*
	* @param AbstractEntity
	* @access public
	*/
	public function update($entity){
		$query = $this->getQueryGenerator()->generateUpdate($entity->getEntityMetadata());
		//var_dump($query);
		$statment = $this->handler->prepare($query);
		$this->bindValsFromMeta($statment,$entity->getEntityMetadata());
		$this->bindKeysFromMeta($statment,$entity->getEntityMetadata());

		$statment->execute();

		return $statment->rowCount();
	}

	/**
	* remove the entity from database
	* @param AbstractEntity $entity
	* 
	* @return integer
	*/
	public function remove($entity){
		$query = $this->getQueryGenerator()->generateRemove($entity->getEntityMetadata());
		//var_dump($query);
		$statment = $this->handler->prepare($query);
		$this->bindKeysFromMeta($statment,$entity->getEntityMetadata());

		$statment->execute();
		return $statment->rowCount();
	}

	/**
	* Get _generator 
	*
	* @return QueryGenerator
	*/
	public function getQueryGenerator(){
		return $this->_generator;
	}

	/**
	* Set _generator 
	* @param QueryGenerator
	* 
	*/
	public function setQueryGenerator(QueryGenerator $generator){
		$this->_generator = $generator;
	}

	/**
	* bind parameters to statement
	* @param PDOStatment
	* @param array entity metadata
	* @param string attached action
	* 
	* @access public
	*/
	private function bindValsFromMeta(&$statment,$entity_metadata,$remove = "_is_primary"){
		foreach ($entity_metadata['_columns'] as $key => $column) {
			if(!in_array($remove, $column) || !$column[$remove])
				$statment->bindValue(':'.$key,$column['_value']);
		}
	}

	/**
	* bind primary keys to statement
	* @param PDOStatment
	* @param array entity metadada
	* 
	* @access public
	*/
	private function bindKeysFromMeta(&$statment,$entity_metadata){
		foreach ($entity_metadata['_columns'] as $key => $column) {
			if(in_array('_is_primary', $column) && $column['_is_primary']){
				$statment->bindValue(':'.$key,$column['_value']);
				break;
			}
		}
	}

	/**
	* get the entity key name
	* @param array entity metadada
	* @param string attached action
	* 
	* @access public
	*/
	private function getEntityPrimaryKey($entity_metadata){
		foreach ($entity_metadata['_columns'] as $key => $column) {
			if(in_array('_is_primary', $column) && $column['_is_primary']){
				return array("key" => $key, "column_name" => $column['_column_name']);
				break;
			}
		}
	}

}