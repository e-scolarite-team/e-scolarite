<?php

require_once("inc/db_engine.php");
require_once("inc/etudiantbase.php");
require_once("inc/etudiant.php");

class Acceuil extends BaseEtudiant
{    
    public function __construct()
    {
        parent::__construct();
    }
    
    public function generer_contenu()
    {
        echo <<<EOC
    <h3 class="titre ombre">Réclamation de bourse</h3>
    <div class="detail-contenu" >
    <p>
        Cette page est consacrée pour effectuer une réclamation de bourse.
    </p>
    <p>
        Pour réaliser la demande commencer par indiquer le type de bourse affichée ensuite indiquer la bourse réclamée et envoyer pour valider votre demande.  
    </p>
    </div><!-- .detail-contenu -->
    <hr style="border-top: #66CE88 solid 2px;margin:0 18%;"/>
    <div class="detail-contenu" >
        <form action="reclamation-bourse-t.php" method="post">
        <fieldset>
            <legend>Réclamation</legend>
            <div class="champ">
                <label>Statut actuel :</label>
                <select name="aff" required="required">
                    <option></option>
                    <option value="1">Non inscrit</option>
                    <option value="2">Bourse non accordée</option>
                    <option value="3">Demi Bourse</option>
                    <option value="5">Double Baccalauréat</option>
                    <option value="6">En cours de traitement</option>
                </select>
            </div><!-- .champ -->
            <div class="champ">
                <label>Bourse réclamée :</label>
                <select name="rec" required="required">
                    <option></option>
                    <option value="3">Demi Bourse</option>
                    <option value="4">Bourse Complète</option>
                </select>
            </div><!-- .champ -->
EOC;
        if($this->msgi!='')
        {
            echo '<div class="info">' . $this->msgi . '</div>';
        }
        if($this->msgr!='')
        {
            echo '<div class="erreur">' . $this->msgr . '</div>';
        }
        echo <<<EOC
            <div class="champ">
                <input type="submit" value="Envoyer" style="margin:0 0 0 160px;" />
            </div><!-- .champ -->
        </fieldset>
        </form>
    </div><!-- .detail-contenu -->
EOC;
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
                    $this->msgi='Votre réclamation a été envoyée avec succès.';
                    break;
                default:
                    $this->msgi='';
                    break;
            }
        }
    }
    
}

$page = new Acceuil();
$page->traiter_requete();