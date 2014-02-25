<?php

require_once("inc/pagebase.php");

abstract class BaseEtudiant extends PageBase
{
    //public $menu = array("reclamation-module.php"=>"Réclamation d'inscription en module","reponse-module.php"=>"Réponse réclamation d'inscription en module","reclamation-note.php"=>"Réclamation note","reponse-note.php"=>"Réponse réclamation note","reclamation-changement.php"=>"Réclamation changement de module","reponse-changement.php"=>"Réponse réclamation changement module","reclamation-bourse.php"=>"Réclamation bourse","reponse-bourse.php"=>"Réponse réclamation bourse","demande-piece.php"=>"Demander une pièce","rdv-piece.php"=>"RDV récupération pièce","demande-libre.php"=>"Module libre","reponse-libre.php"=>"Réponse module libre","releve.php"=>"Consulter les notes","attestation-scolarite.php"=>"Attestation de scolarite","pfe-ins.php"=>"Projet de fin d'études","admission-license.php"=>"Attestation d'admission en license fondamentale","logout.php"=>"Déconnexion");
	public $menu = array("demande-piece.php"=>"Demander une pièce","rdv-piece.php"=>"RDV récupération pièce","demande-libre.php"=>"Module libre","reponse-libre.php"=>"Réponse module libre","logout.php"=>"Déconnexion");
    public function __construct()
    {
        parent::__construct();
    }
    
    public function titre()
    {
        return 'Service Scolarité';
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
    			<div class="ombre">
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
		
	
		<div class="fix"></div>
	</div><!-- #conteneur -->
EOC;
    }

    public function verification()
    {
        if(!is_logged())
        {
            header("location:index.php");
            exit();
        }
    }
    
    public function generer_menu()
    {
        echo <<<EOC
        <h3 class="titre ombre">Menu</h3>
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
}