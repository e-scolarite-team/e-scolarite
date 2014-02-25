<?php

require_once("inc/pagebase.php");
require_once("inc/preinscrit.inc.php");

class Acceuil extends PageBase
{
    public function __construct()
    {
        parent::__construct();
    }
    
    public function genererContenuBody(){}
    public function titre(){}

    public function traiter_donnees()
    {
        if(isset($_POST['cne']) and isset($_POST['date']) and is_numeric($_POST['cne']))
        {
            if($pr = Preinscrit::authPreinscrit($_POST['cne'],$_POST['date']))
            {
                $_SESSION['pcne'] = $pr->cne;
                $_SESSION['pr'] = $pr;
                redirectToPage('preinscription.php');
                exit();
            }
            redirectToPage('preinscription-c.php?err=104');
            exit();
        }
        redirectToPage('preinscription-c.php');
        exit();
    }
    
    public function verification()
    {
        if(is_logged())
        {
            redirectToPage('etudiant.php');
            exit();
        }
    }
    
}

$page = new Acceuil();
$page->traiter_requete();