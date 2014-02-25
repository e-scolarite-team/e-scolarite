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
        return "Traiter les réclamations de changement de module";
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
    <h3 class="titre ombre">Traiter les réclamations de changement de module</h3>
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
                <p>Il y a <strong>$nbr</strong> réclamation de changement de module non traitées.</p>
            </div>
            <hr style="border-top: #66CE88 solid 2px;margin:0 18%;"/>
            <div class="detail-contenu">
                <form action="traiter-changement-t.php" method="post">
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
                        <label>Décision : </label>
                        <input type="radio" name="{$rec['numero']}[decision]" value="1"/><span class="r-acceptee">Accepter</span>
                        <input type="radio" name="{$rec['numero']}[decision]" value="0"/><span class="r-rejetee">Rejeter</span>
                    </li>
                    </div><!-- .reclamation-detail -->
                    <div class="reclamation-detail">
                    <li>
                        <label>Remarque :</label><br />
                        <textarea name="{$rec['numero']}[remarque]"></textarea>
                    </li>
                    </div><!-- .reclamation-detail -->
                    <li class="fix">
                        <fieldset style="float:left;width:45%;">
                            <legend>Modules programmés</legend>
EOC;
                    $i=0;
                    foreach($this->mods as $m)
                    {
                        if(isset($rec['a']) and in_array($m,$rec['a']))
                        {
                            echo "<input type='checkbox' name={$rec['numero']}[a][$m] value='$m' checked='checked' class='mods' />$m";
                        }
                        else
                        {
                            echo "<input type='checkbox' name={$rec['numero']}[a][$m] value='$m' class='mods' />$m";
                        }
                        $i++;
                        if($i%4==0)
                        {
                            echo "<br />";
                        }
                    }
                    echo <<<EOC
                        </fieldset>
                        <fieldset style="float:left;width:45%;">
                            <legend>Modules Réclamés</legend>
EOC;
                    $i=0;
                    foreach($this->mods as $m)
                    {
                        if(isset($rec['r']) and in_array($m,$rec['r']))
                        {
                            echo "<input type='checkbox' name={$rec['numero']}[r][$m] value='$m' checked='checked' class='mods' />$m";
                        }
                        else
                        {
                            echo "<input type='checkbox' name={$rec['numero']}[r][$m] value='$m' class='mods' />$m";
                        }
                        $i++;
                        if($i%4==0)
                        {
                            echo "<br />";
                        }
                    }
                    echo <<<EOC
                        </fieldset>
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
    
    public function verification()
    {
        parent::verification();
        $err=null;
        $stmt=null;
        $req = "SELECT `rec`.`numero`,`rec`.`cne`,DATE_FORMAT(`rec`.`dateenvoi`,GET_FORMAT(DATE,'EUR')) AS `dateenvoi`,`et`.`nom`,`et`.`prenom` FROM `reclamationchanger` AS `rec` INNER JOIN `etudiant` AS `et` ON `rec`.`cne` = `et`.`cne`WHERE isnull(`rec`.`etat`);";
        db_engine::connecter(SERVER,DB_USER,DB_PASS,DB);
        if($stmt = db_engine::preparer($req))
        {
            $stmt->execute();
            $stmt->store_result();
            $stmt->bind_result($num,$cne,$date,$nom,$prenom);
            $i=0;
            while($stmt->fetch())
            {
                $this->reclamation[$i]['numero'] = $num;
                $this->reclamation[$i]['cne'] = $cne;
                $this->reclamation[$i]['date'] = $date;
                $this->reclamation[$i]['nom'] = $nom;
                $this->reclamation[$i]['prenom'] = $prenom;
                $i++;
            }
        }
        if(count($this->reclamation)>0)
        {
            $req = "SELECT `prog`.`moduleaffiche` FROM `reclamationchanger` AS `rec` INNER JOIN `reclamationchangerprog` AS `prog` ON `rec`.`numero` = `prog`.`num` AND `rec`.`numero`=?;";
            if($stmt = db_engine::preparer($req))
            {
                foreach($this->reclamation as &$r)
                {
                    $stmt->bind_param("i",$r['numero']);
                    $stmt->store_result();
                    $stmt->execute();
                    $stmt->bind_result($m);
                    while($stmt->fetch())
                    {
                        $r['a'][] = $m;
                    }
                }
            }
        }
        
        if(count($this->reclamation)>0)
        {
            $req = "SELECT `r`.`modulereclame` FROM `reclamationchanger` AS `rec` INNER JOIN `reclamationchangerrec` AS `r` ON `rec`.`numero` = `r`.`num` AND `rec`.`numero`=?;";
            if($stmt = db_engine::preparer($req))
            {
                foreach($this->reclamation as &$r)
                {
                    $stmt->bind_param("i",$r['numero']);
                    $stmt->store_result();
                    $stmt->execute();
                    $stmt->bind_result($m);
                    while($stmt->fetch())
                    {
                        $r['r'][] = $m;
                    }
                }
            }
        }
        $req = "SELECT `modules`.`mid` FROM `modules` ORDER BY `modules`.`mid`;";
        if($stmt = db_engine::preparer($req))
        {
            $stmt->execute();
            $stmt->store_result();
            $stmt->bind_result($m);
            while($stmt->fetch())
            {
                $this->mods[] = $m;
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