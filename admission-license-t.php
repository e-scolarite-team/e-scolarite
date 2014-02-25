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
        $_SESSION['license'] = $this->generer_pdf();
        redirectToPage("admission-license.php?i=100");
        exit();
    }

    private function generer_pdf()
    {
        $et = $_SESSION['etud'];
        $a = $_POST['annee'] + 1;
        $html =<<<PDF
        <html>
        <head>
            <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
            <style type="text/css">
            h3
            {
                text-align:center;
            }
            .tdb
            {
                margin-top:10px;
            }
            </style>
        </head>
        <body>
            <table border="none">
            <tr>
                <td style="width:250px;">Université Sultan Moulay Slimane<br />Faculté Polydisciplinaire<br />Béni Mellal</td>
                <td style="width:250px;"><img src="img/logo-fp.png" width=200px height=60px /></td>
                <td style="width:250px;text-align:right;">جامعة السلطان مولاي سليمان <h2>الكلية المتعددة الغصائص<br />بني ملال</h2></td>
            </tr>
            </table>
            <div style="text-align:right;">Année Universitaire {$_POST['annee']}-{$a}</div>
            <h3>FICHE POUR L'OBTENTION DE L'ATTESTATION<br />D'ADMISSION EN LICENSE FONDAMENTALE</h3>
            <table border="none" cellpadding="2px">
            <tr>
                <td class="left">Nom et Prénom :</td>
                <td>{$et->nom} {$et->prenom}</td>
            </tr>
            <tr>
                <td class="left">CNE :</td>
                <td>{$et->cne}</td>
            </tr>
            <tr>
                <td class="left">Téléphone :</td>
                <td>{$_POST["tel"]}</td>
            </tr>
            <tr>
                <td class="left">Filière :</td>
                <td>{$this->filiere}</td>
            </tr>
        </table>
        <table border="1" class="tdb">
            <tr>
                <td style="width:176px">Nom Et prénom</td>
                <td style="width:176px">Service ou département</td>
                <td style="width:176px">Date</td>
                <td style="width:176px">Observation</td>
            </tr>
            <tr>
                <td style="width:176px;height:135px;"></td>
                <td style="width:176px;height:135px;" valign="top">Coordinateur de filière</td>
                <td style="width:176px;height:135px;"></td>
                <td style="width:176px;height:135px;"></td>
            </tr>
            <tr>
                <td style="width:176px;height:135px;"></td>
                <td style="width:176px;height:135px;" valign="top">Responsable de la bibliothèque</td>
                <td style="width:176px;height:135px;"></td>
                <td style="width:176px;height:135px;"></td>
            </tr>
            <tr>
                <td style="width:176px;height:135px;"></td>
                <td style="width:176px;height:135px;" valign="top">Encadrant du PFE</td>
                <td style="width:176px;height:135px;"></td>
                <td style="width:176px;height:135px;"></td>
            </tr>
            <tr>
                <td style="width:176px;height:135px;"></td>
                <td style="width:176px;height:135px;" valign="top">Service de Scolarité</td>
                <td style="width:176px;height:135px;"></td>
                <td style="width:176px;height:135px;"></td>
            </tr>
        </table>
        <p style="margin:10px;">Mghila B.P.592 , Béni Mellal 05 23 42 45 97 : 05 23 42 46 85</p>
        </body>
        </html>
PDF;
        return $html;
    }

    public function verification()
    {
        parent::verification();
        $et = $_SESSION['etud'];
        if(empty($_POST['filiere']))
        {
            redirectToPage("admission-license.php");
            exit();
        }
        $nb=0;
        $req = "SELECT `pfe`.`numero` FROM `pfe` INNER JOIN `pfeind` ON `pfe`.`numero`=`pfeind`.`num` WHERE `pfeind`.`cne`=? AND `pfe`.`filiere`=?;";
        db_engine::connecter(SERVER,DB_USER,DB_PASS,DB);
        if($stmt = db_engine::preparer($req))
        {
            $stmt->bind_param('ss',$et->cne,$_POST['filiere']);
            $stmt->execute();
            $stmt->store_result();
            $nb = $stmt->num_rows();
            if($nb<1)
            {
                redirectToPage("admission-license.php?err=106");
                exit();
            }
        }
        else
        {
            redirectToPage("admission-license.php?err=104");
            exit();
        }

        $req = "SELECT `elementpedagogique`.`libele2` FROM `elementpedagogique` WHERE `type`='fil' AND `code`=?;";
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
        else
        {
            redirectToPage("admission-license.php?err=104");
            exit();
        }
    }
}

$page = new Pfe();
$page->traiter_requete();