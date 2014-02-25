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
        return "Traiter les réclamations de bourse";
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
    <h3 class="titre ombre">Traiter les réclamations de bourse</h3>
EOC;
        if($this->msgi!='')
        {
            echo '<div class="info">' . $this->msgi . '</div>';
        }
        if($this->msgr!='')
        {
            echo '<div class="erreur">' . $this->msgr . '</div>';
        }
        $nbr = count($this->reclamation);
        if($nbr==0)
        {
            echo '<p class="detail-contenu">Aucune réclamation à traiter.</p>';
        }
        else
        {
            echo <<<EOC
            <div class="detail-contenu">
                <p>Il y a <strong>$nbr</strong> réclamations de bourse non traitées.</p>
            </div>
            <hr style="border-top: #66CE88 solid 2px;margin:0 18%;"/>
            <div class="detail-contenu">
                <form action="traiter-bourse-t.php" method="post">
                    <p>Valider les décisions en cliquant sur ce bouton : <input type="submit" value="valider" id="valider"/></p>
EOC;
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
                    <li>
                        <label>Remarque :</label><br />
                        <textarea name="{$rec['numero']}[remarque]"></textarea>
                    </li>
                    <li>
                        <label>Décision : </label>
                        <input type="radio" name="{$rec['numero']}[decision]" value="1"/><span class="r-acceptee">Accepter</span>
                        <input type="radio" name="{$rec['numero']}[decision]" value="0"/><span class="r-rejetee">Rejeter</span>
                    </li>
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
        $req = "SELECT `r`.`numero`,`r`.`cne`,`r`.`affiche`,`r`.`reclame`,DATE_FORMAT(`r`.`dateenvoi`,GET_FORMAT(DATE,'EUR')),`e`.`nom`,`e`.`prenom` FROM `reclamationbourse` AS `r` INNER JOIN `etudiant` AS `e` ON `r`.`cne` = `e`.`cne` WHERE isnull(`r`.`etat`);";
        db_engine::connecter(SERVER,DB_USER,DB_PASS,DB);
        if($stmt = db_engine::preparer($req))
        {
            $stmt->execute();
            $stmt->store_result();
            $stmt->bind_result($num,$cne,$aff,$rec,$date,$nom,$prenom);
            $i=0;
            while($stmt->fetch())
            {
                $this->reclamation[$i]['numero'] = $num;
                $this->reclamation[$i]['cne'] = $cne;
                $this->reclamation[$i]['actuel'] = $aff;
                $this->reclamation[$i]['reclame'] = $rec;
                $this->reclamation[$i]['date'] = $date;
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
        if(isset($_GET['i']) and is_numeric($_GET['i']))
        {
            $nb = '';
            if(isset($_GET['nb']) and is_numeric($_GET['nb']))
                $nb= (string)$_GET['nb'];
                
            switch($_GET['i'])
            {
                case 100:
                    $this->msgi="<strong>$nb</strong> Réclamations ont été traitées avec succès.";
                    break;
                default:
                    $this->msgi='';
                    break;
            }
        }
    }
}

$page = new Traiter();
$page->traiter_requete();