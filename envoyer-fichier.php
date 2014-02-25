<?php
require_once("inc/db_engine.php");
require_once("inc/adminbase.php");

class Envoyer extends BaseAdmin
{
    public function __construct()
    {
        parent::__construct();
    }
    
    public function titre()
    {
        return 'Exporter Données';
    }
    
    public function emplacementStyles()
    {
        $styles = array();
        $styles[] = "styles/general.css";
        return $styles;
    }
    
    public function generer_contenu()
    {
        echo <<<EOC
    <h3 class="titre ombre">Envoyer un fichier</h3>
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
    <div style="margin-left:20px;">
    <form method='post' action='envoyer-fichier-t.php' enctype='multipart/form-data' name='envoyer_fichier'>
        <div>
            <div style="margin-bottom:10px">
                <label>Spécifier le type du fichier:</label>
                <select name="fichier" required="required">
                    <option></option>
                    <option value="1">Notes</option>
                    <option value="2">Contrat</option>
                    <option value="3">Eléments pédagoqiques</option>
                    <option value="4">Individus</option>
                </select>
            </div>
            <div style="margin-bottom:10px">
                <label>Selection le fichier à envoyer:</label>
                <input type='file' name='doc' size='30' required="required">
            </div>
            <p align="center"><input type='submit' value='Envoyer'></p>
        </div>
        
    </form>
    </div>
EOC;
    }
    
    public function traiter_donnees()
    {
        parent::traiter_donnees();
        if(isset($_GET['err']))
        {
            if(is_numeric($_GET['err']))
            {
                switch ($_GET['err'])
                {
                    case 104:
                        $this->msgr = "Veuillez choisir un fichier.";
                        break;
                    case 105:
                        $this->msgr = "Erreur d'envoi, vérifier le fichier et ressayer.";
                        break;
                    case 106:
                        $this->msgr = "Le type de fichier est incorrect, choisir un fichier .xlsx ou .csv et ressayer.";
                        break;
                    case 107:
                        $this->msgr = "Aucun type choisis , veuillez spécifier le type du fichier et ressayer.";
                        break;
                    case 108:
                        $this->msgr = "Un problème imprévu est survenu, veuillez ressayer ultérieurement.";
                        break;
                    case 109:
                        $this->msgr = "Erreur de base données, veuillez ressayer ultérieurement.";
                        break;
                    default:
                        $this->msgr = "";
                        break;
                }
            }
        }

        if(isset($_GET['i']))
        {
            if(is_numeric($_GET['i']))
            {
                switch ($_GET['i'])
                {
                    case 100:
                        $this->msgi = "Fichier exporter avec succès.";
                        break;
                    default:
                        $this->msgi = "";
                        break;
                }
            }
        }
    }
}

$page = new Envoyer();
$page->traiter_requete();