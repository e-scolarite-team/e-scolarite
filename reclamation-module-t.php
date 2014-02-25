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
        $id=null;
        if(isset($_POST['r']))
        {
            db_engine::connecter(SERVER,DB_USER,DB_PASS,DB);
            $req = "INSERT INTO `reclamationmodule`(`cne`,`dateenvoi`)VALUES(?,NOW());";
            if($stmt = db_engine::preparer($req))
            {
                $stmt->bind_param("s",$etud->cne);
                $stmt->execute();
                $id = $stmt->insert_id;
            }
            else
            {
                redirectToPage("reclamation-module.php?err=105");
                exit();
            }
            if($id and isset($_POST['a']))
            {
                $req = "INSERT INTO `reclamationmoduleaff`(`num`,`moduleaffiche`)VALUES(?,?);";
                if($stmt = db_engine::preparer($req))
                {
                    foreach($_POST['a'] as $a)
                    {
                        $stmt->bind_param("is",$id,$a);
                        $stmt->execute();
                    }
                }
                else
                {
                    redirectToPage("reclamation-module.php?err=105");
                    exit();
                }
            }
            if($id and isset($_POST['r']))
            {
                $req = "INSERT INTO `reclamationmodulerec`(`num`,`modulereclame`)VALUES(?,?);";
                if($stmt = db_engine::preparer($req))
                {
                    foreach($_POST['r'] as $r)
                    {
                        $stmt->bind_param("is",$id,$r);
                        $stmt->execute();
                    }
                }
                else
                {
                    redirectToPage("reclamation-module.php?err=105");
                    exit();
                }
            }
            $stmt->close();
            db_engine::fermer();
            redirectToPage("reclamation-module.php?i=100");
            exit();
        }
        else
        {
            redirectToPage("reclamation-module.php?err=104");
            exit();
        }
        redirectToPage("reclamation-module.php");
        exit();
    }
}

$page = new Reclamation();
$page->traiter_requete();