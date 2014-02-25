<?php
require_once("inc/db_engine.php");
require_once("inc/adminbase.php");
require_once("inc/exporter-fichier.php");

class Envoyer extends BaseAdmin
{
    public function __construct()
    {
        parent::__construct();
    }
    
    public function titre()
    {
        return 'Exporter Données';
    }
    
    public function emplacementStyles()
    {
        $styles = array();
        $styles[] = "styles/general.css";
        return $styles;
    }
        
    public function traiter_donnees()
    {
        envoi();
    }
    
}

$page = new Envoyer();
$page->traiter_requete();