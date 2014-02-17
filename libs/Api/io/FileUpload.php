<?php

namespace Api\io;

/**
* @author fayssal tahtoub
* @version 0.1
*
*/
class  FileUpload{

	private $attachments;

	public function __construct(){
		$this->attachments = &$_FILES;
	}

	/**
	* test and upload the  file
	* @var string $file file name
	* @var string $dest destination
	* @var array $allows array('size' => 125536, 'ext' => array('jpg', 'png', 'gif'))
	* @var string $prefixe file prefixe
	*
	* @access public
	* @return array array('name' => '' , 'status' => 0|1 )
	*/
	public function upload($file,$dest,$allows,$prefixe = 'upl'){

		if($this->isFileUploaded($file)){

			if($this->checkFile($file,$allows)){

				$new_name = $this->getFileName($file,$prefixe);

				if($this->move($file,$new_name,$dest)){
					return array('name' => $new_name , 'status' => 0 , 'message' => "le fichier a été transférer avec succes");
				}

				return array('name' => '' , 'status' => 1 , 'message' => "imposible de deplacé fichier ");
			
			}else{

				return array('name' => '' , 'status' => 1 , 'message' => "verifier l'extension ou la taille du fichier ");
			}
		}

		return array('name' => '' , 'status' => 1 , 'message' => 'le transfer du fichier a été échoué');
	}

	private function isFileUploaded($file){

		if(array_key_exists($file, $this->attachments) && $this->attachments[$file]['error'] == UPLOAD_ERR_OK)
			return true;
		return false;
	}

	private function getFileName($file,$prefixe){

		$parts = explode('.', $this->attachments[$file]['name']);
		$ext = $parts[count($parts)-1];

		return $this->getUniqueName($prefixe).'.'.$ext;

	}

	private function getUniqueName($prefixe){

		return $prefixe.'_'.uniqid();
	}

	public function move($file,$new_name,$dest){
		return move_uploaded_file($this->attachments[$file]['tmp_name'], $dest.DS.$new_name);
	}

	private function getFileExt($file){
		$parts = explode('.', $this->attachments[$file]['name']);
		$ext = $parts[count($parts)-1];

		return $ext;
	}

	private function checkFile($file,$allows){

		if(!in_array($this->getFileExt($file), $allows['ext']))
			return false;

		if($this->attachments[$file]['size'] > $allows['size'])
			return false;

		return true;
	}
}