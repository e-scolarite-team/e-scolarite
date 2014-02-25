<?php

require_once("inc/pagebase.php");

abstract class BaseAdmin extends PageBase
{
    public function __construct()
    {
        parent::__construct();
    }

    public $menu = array("envoyer-fichier.php"=>"Exporter les données","gerer-piece.php"=>"RDV pièces","traiter-module.php"=>"Traiter les réclamations d'inscription en module","reponse-module-hist.php"=>"Historique des réclamations d'inscription en module","traiter-note.php"=>"Traiter les réclamations de note","reponse-note-hist.php"=>"Historique des réclamations de note","traiter-libre.php"=>"Traiter module libre","reponse-libre-hist.php"=>"Historique module libre","traiter-changement.php"=>"Traiter les réclamations de changement module","reponse-changement-hist.php"=>"Historique des réclamations de changement module","traiter-bourse.php"=>"Traiter les réclamations de bourse","reponse-bourse-hist.php"=>"Historique des réclamations de bourse","traiter-piece.php"=>"Traiter les demandes de pièces","gerer-preinscription.php"=>"Gérer la prèinscription","gerer-reclamation.php"=>"Gérer les réclamations","maj-etudiant.php"=>"Mise à jour des étudiants","logout.php?a=10"=>"Déconnexion");
    
    public function titre()
    {
        return 'Service Scolarité';
    }
    
    public function inlineStyle()
    {
        $st = parent::inlineStyle();
        /*$rouge = rand(0,255);
        $bleu = rand(0,255);
        $vert = rand(0,255);
        $st = $st . " .ombre .titre { background-color: rgb($rouge,$vert,$bleu);}";*/
        return $st;
    }
    
    public function emplacementStyles()
    {
        $styles = array();
        $styles[] = "styles/general.css";
        return $styles;
    }
    
    public function genererContenuBody()
    {
        echo <<<EOC
        <div id="header">
			<img height="130px" src="img/logo-fp.png" alt="Faculté polydisciplinaire Sultan Moulay Slimane Beni Mellal" />
		</div><!-- #header -->
    <div id="conteneur">
		        
        <div id="contenu-g" >
			<div id="main-menu" class="ombre">
EOC;
        $this->generer_menu();
        
        echo <<<EOC
        </div>
		</div><!-- #contenu-g -->
        
        <div id="contenu-d" class="ombre">
EOC;
        $this->generer_contenu();
        echo <<<EOC
        </div><!-- #contenu-d -->
		<div id="maj_aside"></div>
	
		<div class="fix"></div>
	</div><!-- #conteneur -->
EOC;
    }
          
    
    public function generer_menu()
    {
        echo <<<EOC
        <h3 class="titre ombre">Menu Admin</h3>
        <ul>
EOC;
        foreach($this->menu as $lien=>$choix)
        {
            echo "<li><a href='$lien'>$choix</a></li>";
        }
        echo '</ul>';
    }
    
    public function generer_contenu()
    {
        
    }

    public function traiter_donnees()
    {
        parent::traiter_donnees();
        $this->msgr = '';
        $this->msgi = '';
    }
    
    public function verification()
    {
        parent::verification();
        if(is_loggedAdmin()==false)
        {
            redirectToPage("gestion.php");
            exit();
        }
    }
}