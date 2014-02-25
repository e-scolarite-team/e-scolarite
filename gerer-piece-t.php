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
        if(count($_POST)>0)
        {            
            db_engine::connecter(SERVER,DB_USER,DB_PASS,DB);
            $req = "UPDATE `piece` SET `rdv` = ? WHERE `numero`=?;";
            if($stmt = db_engine::preparer($req))
            {
                foreach ($_POST as $key => $value)
                {
                    if(!empty($value))
                        $stmt->bind_param("si",$value,$key);
                    $stmt->execute();            
                }         
                $stmt->close();
                db_engine::fermer();
                redirectToPage("gerer-piece.php?i=100");
                exit();
            }
            redirectToPage("gerer-piece.php?err=104");
            exit();
        }
        redirectToPage("gerer-piece.php");
        exit();
    }
}

$page = new Envoyer();
$page->traiter_requete();