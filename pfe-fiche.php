<?php

require_once("inc/db_engine.php");
require_once("inc/etudiantbase.php");
require_once("inc/etudiant.php");

class Acceuil extends BaseEtudiant
{    
    private $annee;
    private $filiere;
    public function __construct()
    {
        parent::__construct();
    }
    
    public function generer_contenu()
    {

        echo <<<EOC
    <h3 class="titre ombre">Projet de Fin d'études (PFE)</h3>
EOC;
        if($this->msgi!='')
        {
            echo '<div class="info">' . $this->msgi . '</div>';
            echo <<<EOC
            <div class="detail-contenu" >
            <p>
                <a href="pfe-telecharger.php">Télécharger la fiche maintenant.</a>
            </p>
            </div>
EOC;
        }
        if($this->msgr!='')
        {
            echo '<div class="erreur">' . $this->msgr . '</div>';
        }
        
    }
    
    public function emplacementStyles()
    {
        $styles = parent::emplacementStyles();
        $styles[] = "styles/formulaire.css";
        return $styles;
    }
    
    public function traiter_donnees()
    {
        parent::traiter_donnees();
        if(isset($_GET['err']) and is_numeric($_GET['err']))
        {
            switch($_GET['err'])
            {
                case 104:
                    $this->msgr='Un probleme est survenu pendant le traitement de votre demande, veuillez ressayer.';
                    break;
                case 105:
                    $this->msgr='Erreur de configuration du serveur, veuillez ressayer plus tard.';
                    break;
                default:
                    $this->msgr='';
                    break;
            }
        }
        if(isset($_GET['i']) and is_numeric($_GET['i']))
        {
            switch($_GET['i'])
            {
                case 100:
                    $this->msgi='Opération terminée avec succès.';
                    break;
                default:
                    $this->msgi='';
                    break;
            }
        }
    }

    public function verification()
    {
        parent::verification();
        if(empty($_SESSION['pfe']))
        {
            redirectToPage('pfe-ins.php');
            exit();
        }
    }
    
}

$page = new Acceuil();
$page->traiter_requete();