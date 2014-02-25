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
            $req = "UPDATE `demandelibre` SET `remarque` = ?,`etat` = ?,`modules` = ? WHERE `numero`=?;";
            if($stmt = db_engine::preparer($req))
            {
                $nb = 0;
                try
                {
                    foreach($_POST as $num => $rec)
                    {
                        if(isset($rec['decision']))
                        {
                            $md = implode('+',$rec['a']);
                            $rec['decision'] = ($rec['decision'] == 1) ? 1 : 0;
                            $stmt->bind_param("sisi",$rec['remarque'],$rec['decision'],$md,$num);
                            $stmt->execute();
                            $i++;
                        }
                    }
                }
                catch(Exception $e)
                {}
                
                $stmt->close();
                db_engine::fermer();
                redirectToPage("traiter-libre.php?i=100&nb=$i");
                exit();
            }
            redirectToPage("traiter-libre.php?err=104");
            exit();
        }
        redirectToPage("traiter-libre.php");
        exit();
    }
}

$page = new Envoyer();
$page->traiter_requete();