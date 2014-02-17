<?php 

namespace MVC\Models\Entity;

use Api\Database\Persistance\Entity\AbstractEntity;

/**
* @author Fayssal Tahtoyb <fayssal.tahtoub@gmail.com>
*
*/
class Article extends AbstractEntity
{
	/**
	*
	*
	* @var string
	*/
	public $reference;

	/**
	*
	*
	* @var string
	*/
	public $titre;

	/**
	*
	*
	* @var string
	*/
	public $autheur;

	/**
	*
	*
	* @var string
	*/
	public $description;

	/**
	*
	*
	* @var double
	*/
	public $prix;

	/**
	*
	*
	* @var string
	*/
	public $photo;

	/**
	*
	*
	* @var integer
	*/
	public $rubriqueid ;




	
	function __construct()
	{
		
	}

	/**
	* Get the name of class store
	*
	* @return string
	* @access public
	*/
	public function getStoreClass(){
		return "MVC\Models\Store\ArticleStore";
	}

	/**
	* Get the metadata of class 
	*
	* @return array
	* @access public
	*/
	public function getEntityMetadata(){
		return array(   "_table_name" => "article",
						"_class_name" => "MVC\Models\Entity\Article",
						"_columns" => array(
							"reference" => array("_column_name" => "reference" ,"_value" => $this->reference , "_is_primary" => true , "_is_auto" => false),
							"titre" => array("_column_name" => "titre" ,"_value" => $this->titre),
							"autheur" => array("_column_name" => "autheur" ,"_value" => $this->autheur),
							"description" => array("_column_name" => "description" ,"_value" => $this->description),
							"prix" => array("_column_name" => "prix" ,"_value" => $this->prix),
							"photo" => array("_column_name" => "photo" ,"_value" => $this->photo),
							"rubriqueid" => array("_column_name" => "rubriqueid" ,"_value" => $this->rubriqueid),
							),
						);
	}

	/**
	* set the object propety
	*
	* @param array
	* @access public
	*/
	public function push($data){
		$this->reference = $data["reference"];
		$this->titre = $data["titre"];
		$this->autheur = $data["autheur"];
		$this->description = $data["description"];
		$this->prix = $data["prix"];
		$this->photo = $data["photo"];
		$this->rubriqueid = $data["rubriqueid"];
	}

	/**
	* set array value from object 
	*
	* @return array
	* @access public
	*/
	public function toArray(){
		return array(
			"reference" => $this->reference,
			"titre" => $this->titre,
			"autheur" => $this->autheur,
			"description" => $this->description,
			"prix" => $this->prix,
			"photo" => $this->photo,
			"rubriqueid" => $this->rubriqueid,
			);
	}

	/**
	* return a validator Constraints and there message
	* @param bool 
	* @return array
	*/
	public function getValidatorFormat($withKey = false){
		$format =  array(
						array('type' => 'notEmpty' , 'value' => $this->titre, 'message' => "Veuillez saisir le titre de l'article"),
						array('type' => 'notEmpty' , 'value' => $this->autheur, 'message' => "Veuillez saisir le nom de l'autheur"),
						array('type' => 'notEmpty' , 'value' => $this->description, 'message' => "Veuillez saisir la description"),
						array('type' => 'float' , 'value' => $this->prix, 'message' => "Veuillez verifier le prix de l'article"),
						array('type' => 'notEmpty' , 'value' => $this->photo, 'message' => "Veuillez inclure la coverture de l'article"),
						array('type' => 'notEmpty' , 'value' => $this->rubriqueid, 'message' => "Veuillez choisir la rubrique de l'article"),
						);
		if($withKey)
			$format[] = array('type' => 'notEmpty' , 'value' => $this->reference, 'message' => "Veuillez saisir la reference de l'article");
		
		return $format;
	}

	

}