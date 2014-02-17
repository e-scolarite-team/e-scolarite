<?php

namespace MVC\Models\Store;

use Api\Database\Persistance\Store\AbstractStore;
/**
* @author Fayssal tahtoub <fayssal.tahtoub@gmail.com>
*/
class ArticleStore extends AbstractStore
{
	
	function __construct()
	{
		
	}

	public function getEntityName(){
		return  "MVC\Models\Entity\Article";
	}

	public function findByTheme($theme){
		return $this->executeQuery("SELECT * FROM article INNER JOIN rubrique ON article.rubriqueid = rubrique.id WHERE rubrique.theme LIKE '".$theme."'");
	}
}