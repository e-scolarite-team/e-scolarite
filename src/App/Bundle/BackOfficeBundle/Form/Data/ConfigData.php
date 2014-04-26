<?php
namespace App\Bundle\BackOfficeBundle\Form\Data;

use Symfony\Component\Validator\Constraints as Validator;

/**
* 
*/
class ConfigData
{
	/**
	* @var boolean
	*/
	protected $autoDemandeState;

	/**
	* @var integer
	* @Validator\GreaterThanOrEqual(value="1", message="errors.config.auto.amount")
	*/
	protected $autoDemandeAmount;

	/**
	* @var string
	* @Validator\NotBlank(message="errors.config.info.dateformat")
	*/
	protected $infoDateFormat;

	/**
	* @var string
	* @Validator\NotBlank(message="errors.config.info.datetimeformat")
	*/
	protected $infoDatetimeFormat;

	/**
	* @var boolean
	*/
	protected $infoServiceState;

	/**
	* @var integer
	* @Validator\Range(min=1, max=2, invalidMessage="errors.config.info.semester.invalidMessage", maxMessage="errors.config.info.semester.maxMessage", minMessage="errors.config.info.semester.minMessage")
	*/
	protected $infoSemester;

	/**
	* @var integer
	* @Validator\GreaterThanOrEqual(value="2012", message="errors.config.info.yearuniv")
	*/
	protected $infoYear;




	

	/**
	* Set infoYear
	* @param integer $year
	*/
	public function setInfoYear($year){
		$this->infoYear = $year;
	}

	/**
	* Get infoYear
	* @return integer
	*/
	public function getInfoYear(){
		return $this->infoYear ;
	}

	/**
	* Set infoSemester
	* @param integer $semester
	*/
	public function setInfoSemester($semester){
		$this->infoSemester = $semester;
	}

	/**
	* Get infoSemester
	* @return integer
	*/
	public function getInfoSemester(){
		return $this->infoSemester ;
	}



	/**
	* Set autoDemandeState
	* @param boolean $autoDemandeState
	*/
	public function setAutoDemandeState($autoDemandeState){
		$this->autoDemandeState = $autoDemandeState;
	}

	/**
	* Get autoDemandeState
	* @return boolean
	*/
	public function getAutoDemandeState(){
		return $this->autoDemandeState;
	}

	/**
	* Set autoDemandeAmount
	* @param integer $autoDemandeAmount
	*/
	public function setAutoDemandeAmount($autoDemandeAmount){
		$this->autoDemandeAmount = $autoDemandeAmount;
	}

	/**
	* Get autoDemandeAmount
	* @return integer
	*/
	public function getAutoDemandeAmount(){
		return $this->autoDemandeAmount;
	}

	/**
	* Set infoDateFormat
	* @param string $infoDateFormat
	*/
	public function setInfoDateFormat($infoDateFormat){
		$this->infoDateFormat = $infoDateFormat;
	}

	/**
	* Get infoDateFormat
	* @return string
	*/
	public function getInfoDateFormat(){
		return $this->infoDateFormat;
	}

	/**
	* Set infoDatetimeFormat
	* @param string $infoDatetimeFormat
	*/
	public function setInfoDatetimeFormat($infoDatetimeFormat){
		$this->infoDatetimeFormat = $infoDatetimeFormat;
	}

	/**
	* Get infoDatetimeFormat
	* @return string
	*/
	public function getInfoDatetimeFormat(){
		return $this->infoDatetimeFormat;
	}

	/**
	* Set infoServiceState
	* @param boolean $infoServiceState
	*/
	public function setInfoServiceState($infoServiceState){
		$this->infoServiceState = $infoServiceState;
	}

	/**
	* Get infoServiceState
	* @return boolean
	*/
	public function getInfoServiceState(){
		return $this->infoServiceState;
	}


}