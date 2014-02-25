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
        if(isset($_POST['mod']) and is_numeric($_POST['mod']) 
            and isset($_POST['elem']) and is_numeric($_POST['elem'])
            and isset($_POST['pv']) and isset($_POST['rec']) )
        {
            
            db_engine::connecter(SERVER,DB_USER,DB_PASS,DB);
            $req = "INSERT INTO `reclamationelement`(`cne`,`module`,`partie`,`pv`,`reclame`,`dateenvoi`)VALUES(?,?,?,?,?,NOW());";
            if($stmt = db_engine::preparer($req))
            {
                $stmt->bind_param("siiss",$etud->cne,$_POST['mod'],$_POST['elem'],$_POST['pv'],$_POST['rec']);
                $state = $stmt->execute();
                $stmt->close();
                db_engine::fermer();
                if($state==false)
                {
                    redirectToPage("reclamation-note.php?err=104");
                    exit();
                }
                redirectToPage("reclamation-note.php?i=100");
                exit();
            }
            redirectToPage("reclamation-note.php?err=105");
            exit();
        }
        else
        {
            redirectToPage("reclamation-note.php?err=106");
            exit();
        }
    }
    
}

$page = new Reclamation();
$page->traiter_requete();