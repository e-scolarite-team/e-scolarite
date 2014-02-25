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
        $etud = $_SESSION['etud'];
        parent::traiter_donnees();
        if(isset($_POST['piece']) and is_numeric($_POST['piece']) )
        {
            db_engine::connecter(SERVER,DB_USER,DB_PASS,DB);
			$req = "SELECT `rdv` FROM piece WHERE numero = ? ;";
			if($stmt = db_engine::preparer($req))
            {
				$rdv = null;
                $stmt->bind_param("i",$_POST['piece']);
                $stmt->execute();
				$stmt->bind_result($rdv);
				
				$stmt->fetch();
				$stmt->close();
				if(empty($rdv))
				{
					redirectToPage("demande-piece.php?err=107");
					exit();
				}
                
			
				$req = "INSERT INTO `demandepiece`(`cne`,`type`,`envoi`,`rdv`) VALUES(?,?,CURRENT_DATE(),?);";
				if($stmt = db_engine::preparer($req))
				{
					$stmt->bind_param("sis",$etud->cne,$_POST['piece'],$rdv);
					
					$state = $stmt->execute();
					$stmt->close();
					db_engine::fermer();
					if($state==false)
					{
						redirectToPage("demande-piece.php?err=104");
						exit();
					}
					redirectToPage("demande-piece.php?i=100&n={$_POST['piece']}");
					exit();
				}
				redirectToPage("demande-piece.php?err=105");
				exit();
			}
        }
        else
        {
            redirectToPage("demande-piece.php?err=106");
            exit();
        }
    }
}

$page = new Demande();
$page->traiter_requete();