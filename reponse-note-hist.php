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
        return "Traiter les réclamations de note";
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
    <h3 class="titre ombre">Traiter les réclamations de note</h3>
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
                        <label>Module : </label>
                        <select name="{$rec['numero']}[mod]">
EOC;
                $this->choix($rec['module'],"Module");
                echo <<<EOC
                        </select>
                    </li>
                    <li>
                    <label>Elément :</label>
                    <select name="{$rec['numero']}[partie]">
EOC;
                $this->choix($rec['partie'],"Elément",2);
                echo <<<EOC
                    </select>
                    </li>
                    <li>
                        <label>Note sur le PV : </label>
                        <input type="text" name="{$rec['numero']}[pv]" value="{$rec['pv']}"/>
                    </li>
                    <li>
                        <label>Note réclamée : </label>
                        <input type="text" name="{$rec['numero']}[rec]" value="{$rec['reclame']}"/>
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
            </div><!-- .class-contenu -->
EOC;
        }
    }
    
    public function choix($num,$type,$nbr=24)
    {
        for($i=1;$i<=$nbr;$i++)
        {
            if($i==$num)
            {
                echo "<option value='$i' selected='selected'>$type $i</option>";
            }
            else
            {
                echo "<option value='$i'>$type $i</option>";
            }
        }
    }
    
    public function verification()
    {
        parent::verification();
        $req = "SELECT `r`.`numero`,`r`.`cne`,`r`.`module`,`r`.`partie`,`r`.`pv`,`r`.`reclame`,DATE_FORMAT(`r`.`dateenvoi`,GET_FORMAT(DATE,'EUR')),`r`.`remarque`,`e`.`nom`,`e`.`prenom` FROM `reclamationelement` AS `r` INNER JOIN `etudiant` AS `e` ON `r`.cne = `e`.cne WHERE NOT isnull(`r`.`etat`) ORDER BY `r`.`numero` DESC LIMIT 0,100;";
        db_engine::connecter(SERVER,DB_USER,DB_PASS,DB);
        if($stmt = db_engine::preparer($req))
        {
            $stmt->execute();
            $stmt->store_result();
            $stmt->bind_result($num,$cne,$mod,$partie,$pv,$rec,$date,$remarque,$nom,$prenom);
            $i=0;
            while($stmt->fetch())
            {
                $this->reclamation[$i]['numero'] = $num;
                $this->reclamation[$i]['cne'] = $cne;
                $this->reclamation[$i]['module'] = $mod;
                $this->reclamation[$i]['partie'] = $partie;
                $this->reclamation[$i]['pv'] = $pv;
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