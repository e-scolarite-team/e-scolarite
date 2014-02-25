<?php

abstract class Handler
{
    
    public function __construct()
	{
	}
    
    public function traiter_requete()
    {
        $this->traiter_donnees();
    }
    
    protected function traiter_donnees()
    {
        //Traiter les donnees de $_POST $_GET....        
    }
    
    protected function redirectToPage($in_url, $in_contentType = 'html/text')
	{
		header("Location: $in_url");
		header("Content-Type: $in_contentType");
	} 
    
}










?>