<?php

require_once("inc/pagebase.php");
require_once("inc/etudiant.php");

class Acceuil extends PageBase
{
    public function __construct()
    {
        parent::__construct();
    }
    
    public function titre()
    {
        return 'Acceuil';
    }
    
    public function emplacementStyles()
    {
        $styles = array();
        $styles[] = "styles/login.css";
        $styles[] = "styles/general.css";
        return $styles;
    }
    
    public function genererContenuBody()
    {
        echo <<<CON
        <div id="header">
			<img height="130px" src="img/logo-fp.png" alt="Faculté polydisciplinaire Sultan Moulay Slimane Beni Mellal" />
		</div><!-- #header -->
<div class="login">
    <h3 class="titre">Service de scolarité</h3>
    <div class="formulaire">
        <form action="index.php" method="POST">
            <div class="champ">
                <label>CNE</label>
                <input name="cne" required="required" /> <br />
            </div>
            <div class="champ">
                <label>Date naissance</label> 
                <input name="date" type="date" required="required"/> <span style="font-size:12px; color:#FF0000; font-weight:bold">(Année-Mois-Jour)</span>
            </div>
            <div class="champ">
            <input type="submit" value="Connexion" />
            </div>
            
        </form>
    </div>
</div><!-- .login -->


CON;
    }
    
    public function traiter_donnees()
    {
        if($etudiant = Etudiant::authEtudiant($_POST['cne'],$_POST['date']))
        {
            $_SESSION['cne'] = $etudiant->cne;
            $_SESSION['etud'] = $etudiant;            
            redirectToPage('etudiant.php');
            exit();
        }
    }
    
    public function verification()
    {
        if(is_logged())
        {
            redirectToPage('etudiant.php');
            exit();
        }
    }
    
}

$page = new Acceuil();
$page->traiter_requete();