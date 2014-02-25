<?php
require_once("inc/db_engine.php");
require_once("inc/adminbase.php");

class Traiter extends BaseAdmin
{
    public $reclamation;
    public function __construct()
    {
        parent::__construct();
        $this->reclamation=array();
    }
    
    public function titre()
    {
        return "Historique des réclamations de bourse";
    }
    
    public function emplacementStyles()
    {
        $styles = parent::emplacementStyles();
        $styles[] = "styles/reclamation.css";
        return $styles;
    }
    
    public function generer_contenu()
    {
        echo <<<EOC
    <h3 class="titre ombre">Historique des réclamations de bourse</h3>
EOC;
        if($this->msgr!='')
        {
            echo '<div class="erreur">' . $this->msgr . '</div>';
        }
        $nbr = count($this->reclamation);
        if($nbr==0)
        {
            echo '<p class="detail-contenu">Aucune réclamation.</p>';
        }
        else
        {
            echo '<div class="detail-contenu">';
            foreach($this->reclamation as $rec)
            {
                echo <<<EOC
                <fieldset>
                <legend>{$rec['prenom']} {$rec['nom']} - Le {$rec['date']}</legend>
                <ul>
                <div class="reclamation-detail">
                    <li>
                        <label>CNE : </label><span>{$rec['cne']}</span>
                    </li>
                    <li>
                        <label>Nom : </label><span>{$rec['nom']}</span>
                    </li>
                    <li>
                        <label>Prénom : </label><span>{$rec['prenom']}</span>
                    </li>
                    <li>
                        <label>Statut actuel :</label>
                        <select name="{$rec['numero']}[actuel]">
EOC;
                $this->choix($rec['actuel'],1,3);
                echo <<<EOC
                        </select>
                    </li>
                    <li>
                    <label>Bourse réclamée :</label>
                    <select name="{$rec['numero']}[rec]">
EOC;
                $this->choix($rec['reclame'],3,4);
                echo <<<EOC
                    </select>
                    </li>
                    </div><!-- .reclamation-detail -->
                    <div class="reclamation-detail">
                    <li>
                        <label>Remarque :</label><br />
                        <p>{$rec['remarque']}</p>
                    </li>
                    </div><!-- .reclamation-detail -->
                </ul>
                </fieldset>
EOC;
            }
            
            echo <<<EOC
            </form>
            </div><!-- .class-contenu -->
EOC;
        }
    }
    
    public function choix($num,$d,$f)
    {
        $choix ="";
        for($i=$d;$i<=$f;$i++)
        {
            $choix .= "<option ";
            if($i==$num)
                $choix .= 'selected="selected"';
            
            $choix .=" value='$i'>";
            switch($i)
            {
                case 1:
                    $choix .= "Non inscrit";
                    break;
                case 2:
                    $choix .= "Bourse non accordée";
                    break;
                case 3:
                    $choix .= "Demi bourse";
                    break;
                case 4:
                    $choix .= "Bourse complète";
                    break;
                case 5:
                    $choix .= "Double Baccalauréat";
                    break;
                case 6:
                    $choix .= "En cours de traitement";
                    break;
                default:
                    break;
            }
            $choix .= "</option>";
        }
        echo $choix;
    }
    
    public function verification()
    {
        parent::verification();
        $req = "SELECT `r`.`numero`,`r`.`cne`,`r`.`affiche`,`r`.`reclame`,DATE_FORMAT(`r`.`dateenvoi`,GET_FORMAT(DATE,'EUR')),`r`.`remarque`,`e`.`nom`,`e`.`prenom` FROM `reclamationbourse` AS `r` INNER JOIN `etudiant` AS `e` ON `r`.`cne` = `e`.`cne` WHERE NOT isnull(`r`.`etat`) ORDER BY `r`.`numero` DESC LIMIT 0,100;";
        db_engine::connecter(SERVER,DB_USER,DB_PASS,DB);
        if($stmt = db_engine::preparer($req))
        {
            $stmt->execute();
            $stmt->store_result();
            $stmt->bind_result($num,$cne,$aff,$rec,$date,$remarque,$nom,$prenom);
            $i=0;
            while($stmt->fetch())
            {
                $this->reclamation[$i]['numero'] = $num;
                $this->reclamation[$i]['cne'] = $cne;
                $this->reclamation[$i]['actuel'] = $aff;
                $this->reclamation[$i]['reclame'] = $rec;
                $this->reclamation[$i]['date'] = $date;
                $this->reclamation[$i]['remarque'] = $remarque;
                $this->reclamation[$i]['nom'] = $nom;
                $this->reclamation[$i]['prenom'] = $prenom;
                $i++;
            }
        }
    }
    
    public function traiter_donnees()
    {
        parent::traiter_donnees();
        if(isset($_GET['err']) and is_numeric($_GET['err']))
        {
            switch($_GET['err'])
            {
                case 104:
                    $this->msgr='Un probleme est survenu pendant le traitement, veuillez ressayer.';
                    break;
                default:
                    $this->msgr='';
                    break;
            }
        }
    }
}

$page = new Traiter();
$page->traiter_requete();