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
        if(count($_POST)>0 and isset($_POST['etat']) and isset($_POST['sid']) and is_numeric($_POST['etat']) and is_numeric($_POST['sid']))
        {            
            db_engine::connecter(SERVER,DB_USER,DB_PASS,DB);
            $req = "UPDATE `session_module` SET `etat` = ? WHERE `sid`=?;";
            if($stmt = db_engine::preparer($req))
            {
                $stmt->bind_param("ii",$_POST['etat'],$_POST['sid']);
                $stmt->execute();            
                $stmt->close();
                db_engine::fermer();
                redirectToPage("gerer-reclamation.php?i=100");
                exit();
            }
            redirectToPage("gerer-reclamation.php?err=104");
            exit();
        }
        redirectToPage("gerer-reclamation.php");
        exit();
    }
}

$page = new Envoyer();
$page->traiter_requete();