<?php

require_once("inc/pagebase.php");

class Acceuil extends PageBase
{
    private $filiere;
    public function __construct()
    {
        parent::__construct();
        $this->filiere = array();
    }
    
    public function titre()
    {
        return 'Préinscription';
    }

    public function emplacementStyles()
    {
        $styles = array();
        $styles[] = "styles/general.css";
        $styles[] = "styles/preinscription.css";
        return $styles;
    }

    public function genererContenuBody()
    {
        echo <<<CON
        <div id="header">
            <img height="130px" src="img/logo-fp.png" alt="Faculté polydisciplinaire Sultan Moulay Slimane Beni Mellal" />
        </div><!-- #header -->
        <div class="pr-conteneur">
            <div class="info-right">جامعة السلطان مولاي سليمان<h2>الكلية المتعددة التخصصات<br />بني ملال</h2></div>
            <div class="info-left">Université Sultan Moulay Slimane<h2>Faculté Polydisciplinaire<br />Béni Mellal</h2></div>
            <h3>Préinscription</h3>
            <fieldset>
            <legend>Félicitation</legend>
            <p>
                Votre préinscription est terminée avec succès.
            </p>
            <p>
                Dérnière étape : Telecharger la fiche de préinscription pour valider votre inscription auprès de la faculté.
            </p>
            <p>
                Télécharger : <a href="fiche-preinscription.php" >Fiche de préinscription</a>
            </p>
            </fieldset>
            </div><!-- .pr-conteneur -->
CON;
    }
    
    public function verification()
    {
        parent::verification();
        if(is_logged())
        {
            redirectToPage('etudiant.php');
            exit();
        }
        if(!isset($_SESSION['pcne']) or !isset($_SESSION['pr']) or !isset($_SESSION['html']))
        {
            redirectToPage('preinscription.php');
            exit();
        }
    }
    
    
}

$page = new Acceuil();
$page->traiter_requete();