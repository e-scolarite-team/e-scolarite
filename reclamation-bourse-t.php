<?php

require_once("inc/db_engine.php");
require_once("inc/etudiantbase.php");
require_once("inc/etudiant.php");

class Reclamation extends BaseEtudiant
{    
    public function __construct()
    {
        parent::__construct();
    }

    public function traiter_donnees()
    {
        $etud = $_SESSION['etud'];
        parent::traiter_donnees();
        if(isset($_POST['aff']) and is_numeric($_POST['aff']) 
            and isset($_POST['rec']) and is_numeric($_POST['rec']))
        {
            db_engine::connecter(SERVER,DB_USER,DB_PASS,DB);
            $req = "INSERT INTO `reclamationbourse`(`cne`,`affiche`,`reclame`,`dateenvoi`)VALUES(?,?,?,NOW());";
            if($stmt = db_engine::preparer($req))
            {
                $stmt->bind_param("sii",$etud->cne,$_POST['aff'],$_POST['rec']);
                
                $state = $stmt->execute();
                $stmt->close();
                db_engine::fermer();
                if($state==false)
                {
                    redirectToPage("reclamation-bourse.php?err=104");
                    exit();
                }
                redirectToPage("reclamation-bourse.php?i=100");
                exit();
            }
            redirectToPage("reclamation-bourse.php?err=105");
            exit();
        }
        else
        {
            redirectToPage("reclamation-bourse.php?err=106");
            exit();
        }
    }
    
}

$page = new Reclamation();
$page->traiter_requete();