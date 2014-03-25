<?php
namespace App\Bundle\BackOfficeBundle\Form\Data;

use Symfony\Component\Validator\Constraints as Validator;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
* 
*/
class ImportData
{
	/**
	* @var string
	* @Validator\NotBlank(message="errors.import.table")
	* @Validator\Choice(choices={"etudiant","filiere","module","element","note"},message="errors.import.table")
	*/
	private $table;

	/**
	* @var UploadedFile
	* @Validator\File(
	* 	uploadErrorMessage = "errors.import.attachment",
	* 	maxSize = "100M",
	* 	mimeTypesMessage = "errors.import.attachment.mimeTypes",
	* 	mimeTypes={
	*			   		"application/vnd.openxmlformats-officedocument.spreadsheetml.sheet",
	*   		   		"application/vnd.ms-excel",
	*              		"text/csv"
	*			   }
	* )
	* @Validator\NotBlank(message="errors.import.attachment")
	*/
	private $attachement;

	/**
	* Set table
	* @param string $table
	*/
	public function setTable($table){
		$this->table = $table;
	}

	/**
	* Get table
	* @return string
	*/
	public function getTable(){
		return $this->table ;
	}


	/**
	* Set attachement
	* @param UploadedFile $attachement
	*/
	public function setAttachement($attachement){
		$this->attachement = $attachement;
	}

	/**
	* Get attachement
	* @return UploadedFile
	*/
	public function getAttachement(){
		return $this->attachement ;
	}
}