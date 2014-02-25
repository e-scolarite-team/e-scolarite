<?php

require_once("inc/db_engine.php");
require_once("inc/etudiantbase.php");
require_once("inc/etudiant.php");

class Demande extends BaseEtudiant
{    
    public function __construct()
    {
        parent::__construct();
    }

    public function traiter_donnees()
    {
        parent::traiter_donnees();
        $etud = $_SESSION['etud'];
        if(isset($_POST['a']) )
        {
            $md = implode('+',$_POST['a']);
            db_engine::connecter(SERVER,DB_USER,DB_PASS,DB);
            $req = "INSERT INTO `demandelibre`(`cne`,`modules`,`envoi`)VALUES(?,?,NOW());";
            if($stmt = db_engine::preparer($req))
            {
                $stmt->bind_param("ss",$etud->cne,$md);
                $state = $stmt->execute();
                $stmt->close();
                db_engine::fermer();
                if($state==false)
                {
                    redirectToPage("demande-libre.php?err=105");
                    exit();
                }
                redirectToPage("demande-libre.php?i=100");
                exit();
            }
            redirectToPage("demande-libre.php?err=105");
            exit();
        }
        redirectToPage("demande-libre.php");
        exit();
    }
    public function verification()
    {
        parent::verification();
        if( ! isset($_POST['a']) )
        {
            redirectToPage("demande-libre.php?err=104");
            exit();
        }
    }
}

$page = new Demande();
$page->traiter_requete();