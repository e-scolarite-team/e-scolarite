<?php

require_once("inc/db_engine.php");
require_once("inc/etudiantbase.php");
require_once("inc/etudiant.php");

class Pfe extends BaseEtudiant
{    
    private $filiere;
    public function __construct()
    {
        parent::__construct();
    }

    public function traiter_donnees()
    {
        $id=null;
        if(!empty($_POST['e1']))
        {
            db_engine::connecter(SERVER,DB_USER,DB_PASS,DB);
            $req = "INSERT INTO `pfe` (`annee`,`intitule`,`encadrant`,`filiere`) VALUES(?,?,?,?);";
            if($stmt = db_engine::preparer($req))
            {
                $stmt->bind_param("isss",$_POST['annee'],$_POST['intitule'],$_POST['encadrant'],$_POST['filiere']);
                $stmt->execute();
                $id = $stmt->insert_id;
            }
            else
            {
                redirectToPage("pfe-ins.php?err=105");
                exit();
            }
            //etudiant 1
            if($id and is_numeric($_POST['e1']['cne']))
            {
                $req = "INSERT INTO `pfeind`(`num`,`cne`,`nom`,`tel`,`email`)VALUES(?,?,?,?,?);";
                if($stmt = db_engine::preparer($req))
                {
                    $stmt->bind_param("issss",$id,$_POST['e1']['cne'],$_POST['e1']['nom'],$_POST['e1']['tel'],$_POST['e1']['email']);
                    $stmt->execute();
                }
                else
                {
                    redirectToPage("rpfe-ins.php?err=105");
                    exit();
                }
            }
            //etudiant 2
            if($id and is_numeric($_POST['e2']['cne']))
            {
                $req = "INSERT INTO `pfeind`(`num`,`cne`,`nom`,`tel`,`email`)VALUES(?,?,?,?,?);";
                if($stmt = db_engine::preparer($req))
                {
                    $stmt->bind_param("issss",$id,$_POST['e2']['cne'],$_POST['e2']['nom'],$_POST['e2']['tel'],$_POST['e2']['email']);
                    $stmt->execute();
                }
                else
                {
                    redirectToPage("rpfe-ins.php?err=105");
                    exit();
                }
            }
            //etudiant 3
            if($id and is_numeric($_POST['e3']['cne']))
            {
                $req = "INSERT INTO `pfeind`(`num`,`cne`,`nom`,`tel`,`email`)VALUES(?,?,?,?,?);";
                if($stmt = db_engine::preparer($req))
                {
                    $stmt->bind_param("issss",$id,$_POST['e3']['cne'],$_POST['e3']['nom'],$_POST['e3']['tel'],$_POST['e3']['email']);
                    $stmt->execute();
                }
                else
                {
                    redirectToPage("rpfe-ins.php?err=105");
                    exit();
                }
            }
            
            @$stmt->close();
            db_engine::fermer();
            $_SESSION['pfe'] = $this->generer_pdf();
            redirectToPage("pfe-fiche.php?i=100");
            exit();
        }
        else
        {
            redirectToPage("pfe-ins.php?err=104");
            exit();
        }
        redirectToPage("pfe-ins.php");
        exit();
    }

    private function generer_pdf()
    {
        $a = $_POST['annee'] + 1;
        $html =<<<PDF
        <html>
        <head>
            <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
            <style type="text/css">
            .left
            {
                width:240px;
            }
            h3
            {
                text-align:center;
            }
            </style>
        </head>
        <body>
            <div style="float:right;width:260px;">Année Universitaire {$_POST['annee']}-{$a}</div>
            <div style="text-align:center;width:40%">Université Sultan Moulay Slimane<br />Faculté Polydisciplinaire<br />Béni Mellal</div>

            <h3>Fiche du Projet de Fin d'études (PFE)</h3>
            <h3>(Filière {$this->filiere})</h3>
            <table border="none" cellpadding="2px">
            <tr>
                <td colspan="2"><b><u>Etudiant 1</u></b></td>
            </tr>
            <tr>
                <td class="left">Nom et Prénom :</td>
                <td>{$_POST['e1']["nom"]}</td>
            </tr>
            <tr>
                <td class="left">CNE :</td>
                <td>{$_POST['e1']["cne"]}</td>
            </tr>
            <tr>
                <td class="left">Téléphone :</td>
                <td>{$_POST['e1']["tel"]}</td>
            </tr>
            <tr>
                <td class="left">E-mail :</td>
                <td>{$_POST['e1']["email"]}</td>
            </tr>

            <tr>
                <td colspan="2"><b><u>Etudiant 2</u></b></td>
            </tr>
            <tr>
                <td class="left">Nom et Prénom :</td>
                <td>{$_POST['e2']["nom"]}</td>
            </tr>
            <tr>
                <td class="left">CNE :</td>
                <td>{$_POST['e2']["cne"]}</td>
            </tr>
            <tr>
                <td class="left">Téléphone :</td>
                <td>{$_POST['e2']["tel"]}</td>
            </tr>
            <tr>
                <td>E-mail :</td>
                <td>{$_POST['e2']["email"]}</td>
            </tr>

            <tr>
                <td colspan="2"><b><u>Etudiant 3</u></b></td>
            </tr>
            <tr>
                <td class="left">Nom et Prénom :</td>
                <td>{$_POST['e3']["nom"]}</td>
            </tr>
            <tr>
                <td class="left">CNE :</td>
                <td>{$_POST['e3']["cne"]}</td>
            </tr>
            <tr>
                <td class="left">Téléphone :</td>
                <td>{$_POST['e3']["tel"]}</td>
            </tr>
            <tr>
                <td class="left">E-mail :</td>
                <td>{$_POST['e3']["email"]}</td>
            </tr>
            <tr>
                <td colspan="2"><hr /></td>
            </tr>
            <tr style="margin-top:20px">
                <td class="left">Intitule du projet :</td>
                <td>{$_POST["intitule"]}</td>
            </tr>
            <tr>
                <td class="left">Nom de l'enseignant encadrant :</td>
                <td>{$_POST["encadrant"]}</td>
            </tr>
        </table>
        <table style="margin-top:20px;" border="1px">
            <tr>
                <td valign="top" style="width:355px;height:120px;">Signature de l'étudiant (e)</td>
                <td valign="top" style="width:355px;height:120px;">Signature de l'encadrant</td>
            </tr>
            <tr>
                <td valign="top" align="center" colspan="2" style="height:110px;">Signature du responsable de la filière</td>
            </tr>
        </table>
        </body>
        </html>
PDF;
        return $html;
    }

    public function verification()
    {
        parent::verification();
        $req = "SELECT `elementpedagogique`.`libele1` FROM `elementpedagogique` WHERE `type`='fil' AND `code`=?;";
        db_engine::connecter(SERVER,DB_USER,DB_PASS,DB);
        if($stmt = db_engine::preparer($req))
        {
            $stmt->bind_param('s',$_POST['filiere']);
            $stmt->execute();
            $stmt->store_result();
            $stmt->bind_result($fil);
            while($stmt->fetch())
            {
                $this->filiere = $fil;
            }
        }
    }
}

$page = new Pfe();
$page->traiter_requete();