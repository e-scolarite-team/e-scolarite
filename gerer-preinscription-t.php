<?php
require_once("inc/db_engine.php");
require_once("inc/adminbase.php");

class Envoyer extends BaseAdmin
{
    public function __construct()
    {
        parent::__construct();
    }
        
    public function traiter_donnees()
    {
        parent::traiter_donnees();
        if(count($_POST)>0 and is_numeric($_POST['etat']))
        {            
            db_engine::connecter(SERVER,DB_USER,DB_PASS,DB);
            $req = "UPDATE `gererpreinscription` SET `annee` = ?,`etat` = ? WHERE `id`=1;";
            if($stmt = db_engine::preparer($req))
            {
                if(!isset($_POST['annee']) or trim($_POST['annee'])=='')
                    $annee = 2000;
                else
                    $annee = $_POST['annee'];
                $stmt->bind_param("si",$annee,$_POST['etat']);
                $stmt->execute();            
                $stmt->close();
                db_engine::fermer();
                redirectToPage("gerer-preinscription.php?i=100");
                exit();
            }
            redirectToPage("gerer-preinscription.php?err=104");
            exit();
        }
        redirectToPage("gerer-preinscription.php");
        exit();
    }
}

$page = new Envoyer();
$page->traiter_requete();