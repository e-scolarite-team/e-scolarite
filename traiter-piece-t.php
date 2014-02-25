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
            $req = "UPDATE `demandepiece` SET `rdv` = ?,`remarque` = ?,`etat` = ? WHERE `numero`=?;";
            if($stmt = db_engine::preparer($req))
            {
                $nb = 0;
                try
                {
                    foreach($_POST as $num => $rec)
                    {
                        if(isset($rec['decision']))
                        {
                            $rec['decision'] = ($rec['decision'] == 1) ? 1 : 0;
                            $stmt->bind_param("ssii",$_POST['rdv'],$rec['remarque'],$rec['decision'],$num);
                            $stmt->execute();
                            $i++;
                        }
                    }
                }
                catch(Exception $e)
                {}
                
                $stmt->close();
                db_engine::fermer();
                redirectToPage("traiter-piece.php?i=100&nb=$i");
                exit();
            }
            redirectToPage("traiter-piece.php?err=104");
            exit();
        }
        redirectToPage("traiter-piece.php");
        exit();
    }
}

$page = new Envoyer();
$page->traiter_requete();