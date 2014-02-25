<?php
require_once("inc/db_engine.php");
require_once("inc/adminbase.php");

class Traiter extends BaseAdmin
{
    public $reclamation;
    public $mods;
    public function __construct()
    {
        parent::__construct();
        $this->reclamation=array();
    }
    
    public function titre()
    {
        return "Traiter les réclamations d'inscription en module";
    }
    
    public function emplacementStyles()
    {
        $styles = parent::emplacementStyles();
        $styles[] = "styles/moteur-recherche.css";
        return $styles;
    }
    
    public function emplacementScript()
    {
        $script = parent::emplacementScript();
        $script[] = "scripts/jquery.js";
        $script[] = "scripts/maj-etudiant.js";
        return $script;
    }
    
    public function generer_contenu()
    {
        echo <<<EOC
    <h3 class="titre ombre">Mise à jour des informations des étudiants.</h3>
    <div class="detail-contenu">
        <div id="block-recherche">
            <form action="recherche.php" method="post">
            <label>Nom étudiant : </label><br />
            <input type="text" name="nom" id="nom" autocomplete="off" /> <input type="submit" id="rechercher" class="valider" value="rechercher" /><div id="ajax-loader"><img width="40px" height="40px" src="img/ajax-loader.gif" alt="Chargement..." /></div>
            </form>
        </div><!-- .block-recherche -->
    </div><!-- .detail-contenu -->
    <div id="suggestion"></div>
EOC;
    }
    
}

$page = new Traiter();
$page->traiter_requete();