<?php

require_once("inc/pagebase.php");
require_once("inc/preinscrit.inc.php");

class Acceuil extends PageBase
{
    public function __construct()
    {
        parent::__construct();
    }
    
    public function titre(){}
    public function emplacementStyles(){}
    public function genererContenuBody(){}

    public function traiter_donnees()
    {
        parent::traiter_donnees();
        $pr = $_SESSION['pr'];
        $nb=0;
        $stmt=null;
        if( $_SERVER['REQUEST_METHOD'] == 'POST' and !empty($_POST['cne']) )
        {
            db_engine::connecter(SERVER,DB_USER,DB_PASS,DB);
            $req = "SELECT preinscrit.cne FROM preinscrit WHERE preinscrit.cne=? AND preinscrit.filiere=?;";
            if($stmt = db_engine::preparer($req))
            {
                $stmt->bind_param("ss",$pr->cne,$_POST['filiere']);
                $state = $stmt->execute();
                $stmt->store_result();
                $nb = $stmt->num_rows();
            }
            else
            {
                redirectToPage("preinscription.php?err=104");
                exit();
            }
            if($nb>0)
            {
                $req = "UPDATE `preinscrit` SET `nom` = ?,`prenom` = ?, `datenaissance` = ?,`sexe` = ?,`cin` = ?,`nomar` = ?,`prenomar` = ?,`tel` = ?,`villenaissance` = ?,`dateins` = ?,`pays` = ?,`adresse` = ?,`email` = ?,`anneebac` = ?,`mention` = ?,`lycee` = ? WHERE preinscrit.cne=? AND preinscrit.filiere=?;";
                if($stmt = db_engine::preparer($req))
                {
                    $stmt->bind_param("sssssssssisssissss",$_POST['nom'],$_POST['prenom'],$pr->dateNaiss,$_POST['sexe'],$_POST['cin'],$_POST['nomar'],$_POST['prenomar'],$_POST['telephone'],$_POST['lnaiss'],$_POST['a'],$_POST['pays'],$_POST['adrperso'],$_POST['email'],$_POST['anneebac'],$_POST['mention'],$_POST['lycee'],$pr->cne,$_POST['filiere']);
                    $stmt->execute();
                }
                else
                {
                    redirectToPage("preinscription.php?err=104");
                    exit();
                }
            }
            else
            {
                $req = "INSERT INTO `preinscrit`(`cne`,`filiere`,`nom`,`prenom`,`datenaissance`,`sexe`,`cin`,`nomar`,`prenomar`,`tel`,`villenaissance`,`dateins`,`pays`,`adresse`,`email`,`anneebac`,`mention`,`lycee`)VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?);";
                if($stmt = db_engine::preparer($req))
                {
                    $stmt->bind_param("sssssssssssisssiss",$pr->cne,$_POST['filiere'],$_POST['nom'],$_POST['prenom'],$pr->dateNaiss,$_POST['sexe'],$_POST['cin'],$_POST['nomar'],$_POST['prenomar'],$_POST['telephone'],$_POST['lnaiss'],$_POST['a'],$_POST['pays'],$_POST['adrperso'],$_POST['email'],$_POST['anneebac'],$_POST['mention'],$_POST['lycee']);
                    $stmt->execute();
                }
                else
                {
                    redirectToPage("preinscription.php?err=104");
                    exit();
                }
            }
            @$stmt->close();
            db_engine::fermer();
            $html = $this->generer_pdf();
            $_SESSION['html'] = $html;
            redirectToPage("preinscription-terminer.php");
            exit();
        }
        else
        {
            redirectToPage("preinscription.php");
            exit();
        }
    }
    
    public function verification()
    {
        parent::verification();
        if(is_logged())
        {
            redirectToPage('etudiant.php');
            exit();
        }
        if(!isset($_SESSION['pcne']) or !isset($_SESSION['pr']) or !isset($_POST['valider']))
        {
            redirectToPage('preinscription-c.php');
            exit();
        }
    }

    private function generer_pdf()
    {
        $d = $_POST['a'] + 1;
        $pr = $_SESSION['pr'];
        $h = ($_POST['handicap'] ==1) ? 'Oui' : 'Non';
        $html = <<<HTML
  <html>
      <head>
            <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
            <style type="text/css">
                .ar
                {
                    direction: rtl;
                }
                
                h3
                {
                    text-align: center;
                    margin-top: 15px;
                    font-size: 1.1em;
                }
                
                .dt
                {
                    width:260px;
                }
                
                fieldset
                {
                    margin-top:10px;
                    border: 1px solid #AAA;
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
            <h3 style="font-size:0.95em;font-weight:100;margin:5px 0;;">Année universitaire: {$_POST['a']}-{$d}</h3>
            <h3>Fiche de Préinscription</h3>

            <p>Filière : {$_POST['filiere']}</p>
        <fieldset>
            <legend>Etat civil</legend>
            <table border="none">
                <tr>
                    <td class="dt">CNE : </td><td>{$pr->cne}</td>
                </tr>
                <tr>
                    <td class="dt">Nom : </td><td>{$_POST['nom']}</td>
                </tr>
                <tr>
                    <td class="dt">Prénom : </td><td>{$_POST['prenom']}</td>
                </tr>
                <tr>
                    <td class="dt">Nom (arabe) : </td><td class="ar">{$_POST['nomar']}</td>
                </tr>
                <tr>
                    <td class="dt">Prénom (arabe) : </td><td class="ar">{$_POST['prenomar']}</td>
                </tr>
                <tr>
                    <td class="dt">CIN : </td><td>{$_POST['cin']}</td>
                </tr>
                <tr>
                    <td class="dt">Date de naissance : </td><td>{$pr->dateNaiss}</td>
                </tr>
                <tr>
                    <td class="dt">Lieu de naissance : </td><td>{$_POST['lnaiss']}</td>
                </tr>
                <tr>
                    <td class="dt">Lieu de naissance : </td><td class="ar">{$_POST['lnaissar']}</td>
                </tr>
                <tr>
                    <td class="dt">Situation familiale : </td><td>{$_POST['sfam']}</td>
                </tr>
                <tr>
                    <td class="dt">Province de naissance : </td><td class="dt">{$_POST['provnaiss']}</td>
                </tr>
                <tr>
                    <td class="dt">Pays : </td><td>{$_POST['pays']}</td>
                </tr>
                <tr>
                    <td class="dt">Sexe : </td><td>{$_POST['sexe']}</td>
                </tr>
                <tr>
                    <td class="dt">Adresse Personnel : </td><td>{$_POST['adrperso']}</td>
                </tr>
                <tr>
                    <td class="dt">Ville : </td><td>{$_POST['ville']}</td>
                </tr>
                <tr>
                    <td class="dt">Téléphone personnel : </td><td>{$_POST['telephone']}</td>
                </tr>
                <tr>
                    <td class="dt">E-mail : </td><td>{$_POST['email']}</td>
                </tr>
                <tr>
                    <td class="dt">Nationalité : </td><td>{$_POST['nationalite']}</td>
                </tr>
                <tr>
                    <td class="dt">Passeport : </td><td>{$_POST['passeport']}</td>
                </tr>
            </table>
            </fieldset>
            <fieldset>
                <legend>Baccalauréat</legend>
                <table border="none">
                <tr>
                    <td class="dt">Année d'obtention du bac : </td><td>{$_POST['anneebac']}</td>
                </tr>
                <tr>
                    <td class="dt">Série du bac : </td><td>{$pr->seriebac}</td>
                </tr>
                <tr>
                    <td class="dt">Lycée : </td><td>{$_POST['lycee']}</td>
                </tr>
                <tr>
                    <td class="dt">Province : </td><td>{$pr->province}</td>
                </tr>
                <tr>
                    <td class="dt">Mention du bac : </td><td>{$_POST['mention']}</td>
                </tr>
                <tr>
                    <td class="dt">Académie : </td><td>{$pr->academie}</td>
                </tr>
                </table>
            </fieldset>
            <p style="page-break-before:always;"></p>
            <fieldset>
            <legend>Parents</legend>
            <table border="none">
                <tr>
                    <td class="dt">Nom et Prénom du père : </td><td>{$_POST['nompere']}</td>
                </tr>
                <tr>
                    <td class="dt">Nom et Prénom de la mère : </td><td>{$_POST['nommere']}</td>
                </tr>
                <tr>
                    <td class="dt">Nom et Prénom du tuteur : </td><td>{$_POST['nomtuteur']}</td>
                </tr>
                <tr>
                    <td class="dt">Adresse actuelle des parents : </td><td>{$_POST['adrparent']}</td>
                </tr>
                <tr>
                    <td class="dt">Commune : </td><td>{$_POST['commune']}</td>
                </tr>
                <tr>
                    <td class="dt">Province ou préfecture : </td><td>{$_POST['provparent']}</td>
                </tr>
                <tr>
                    <td class="dt">Téléphone : </td><td>{$_POST['telparent']}</td>
                </tr>
                <tr>
                    <td class="dt">Groupe socioprofessionel:</td>
                </tr>
                <tr>
                    <td class="dt">Père : </td><td>{$_POST['profpere']}</td>
                </tr>
                <tr>
                    <td class="dt">Mère : </td><td>{$_POST['profmere']}</td>
                </tr>
            </table>
            </fieldset>
            <fieldset>
            <legend>Informations Complémentaires</legend>
            <table border="none">
                <tr>
                    <td class="dt">Fonction(si vous travaillez) : </td><td>{$_POST['fonction']}</td>
                </tr>
                <tr>
                    <td class="dt">Organisme employeur : </td><td>{$_POST['employeur']}</td>
                </tr>
                <tr>
                    <td class="dt">Handicapé(e) : </td><td>{$h}</td>
                </tr>
                <tr>
                    <td class="dt">Nature d'handicap : </td><td>{$_POST['natureh']}</td>
                </tr>
            </table>
            </fieldset>
        </body></html>
HTML;
        return $html;
        
    }
}

$page = new Acceuil();
$page->traiter_requete();