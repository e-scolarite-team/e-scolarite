<?php

require_once("inc/db_engine.php");
require_once("inc/etudiantbase.php");
require_once("inc/etudiant.php");

class Acceuil extends BaseEtudiant
{
    private $etudiant;
    
    public function __construct()
    {
        parent::__construct();
    }
    
    public function generer_contenu()
    {
        $this->etudiant = $_SESSION['etud'];
        echo <<<EOC
        <h3 class="titre ombre">Acceuil</h3>
        <div class="detail-contenu" >
            <h4>Bienvenue !</h3>
            <p>Vous êtes maintenant connecté au service de scolarité en ligne.</p>
            <fieldset>
                <legend>Vos informations</legend>
                <p>CNE : {$this->etudiant->cne}</p>
                <p>Nom : {$this->etudiant->nom}</p>
                <p>Prénom : {$this->etudiant->prenom}</p>
                <p>Sexe : {$this->etudiant->sexe}</p>
                <p>Date naissance : {$this->etudiant->dateNaiss}</p>
                <p>CIN : {$this->etudiant->cin}</p>
                <p>Ville naissance : {$this->etudiant->ville}</p>
            </fieldset>
        </div><!-- .detail-contenu -->
EOC;
    }
    
    
    
}

$page = new Acceuil();
$page->traiter_requete();