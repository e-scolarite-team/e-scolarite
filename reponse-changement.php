<?php
require_once("inc/db_engine.php");
require_once("inc/etudiantbase.php");
require_once("inc/etudiant.php");

class Reponse extends BaseEtudiant
{
    public $reclamation;
    public $mods;
    public $etudiant;
    public function __construct()
    {
        parent::__construct();
        $this->reclamation=array();
    }
    
    public function titre()
    {
        return "Statut des réclamations de changement de module";
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
    <h3 class="titre ombre">Statuts des  réclamations de changement module</h3>
EOC;
        
        $nbr = count($this->reclamation);
        if($nbr==0)
        {
            echo '<p class="detail-contenu">Aucune réponse pour le moment.</p>';
        }
        else
        {
            echo <<<EOC
            <div class="detail-contenu">
                <p>Vous avez <strong>$nbr</strong> réclamations de changement de module.</p>
            </div>
            <hr style="border-top: #4265EF solid 2px;margin:0 18%;"/>
            <div class="detail-contenu">
EOC;
            foreach($this->reclamation as $rec)
            {
                $style = ($rec['etat'] == 1) ? "positif" : "negatif";
                $rec['etat'] = ($rec['etat'] == 1) ? "Acceptée" : "Rejetée";
                echo <<<EOC
                <fieldset>
                <legend>Envoyée Le {$rec['date']}</legend>
                    <div class="$style">{$rec['etat']}</div>
                    <fieldset style="width:45%;">
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
                        <fieldset style="width:45%;">
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
                    echo "</fieldset>";
                if(trim($rec['remarque']) != '')
                {
                    echo <<<EOC
                    <p>Remarque :</p>
                    <div class="remarque">
                        <p>{$rec['remarque']}</p>
                    </div>
EOC;
                }
                echo "</fieldset>";
            }
            
            echo <<<EOC
            </div><!-- ."detail-contenu" -->
            </div><!-- .class-contenu -->
EOC;
        }
    }
    
    
    public function verification()
    {
        parent::verification();
        $this->etudiant = $_SESSION['etud'];
        $req = "SELECT `rec`.`numero`,DATE_FORMAT(`rec`.`dateenvoi`,GET_FORMAT(DATE,'EUR')) AS `dateenvoi`,`rec`.`remarque`,`rec`.`etat` FROM `reclamationchanger` AS `rec`  WHERE `rec`.`cne` = ?  AND NOT isnull(`rec`.`etat`) ORDER BY `rec`.`numero` DESC;";
        db_engine::connecter(SERVER,DB_USER,DB_PASS,DB);
        if($stmt = db_engine::preparer($req))
        {
            $stmt->bind_param("s",$this->etudiant->cne);
            $stmt->execute();
            $stmt->store_result();
            $stmt->bind_result($num,$date,$remarque,$etat);
            $i=0;
            while($stmt->fetch())
            {
                $this->reclamation[$i]['numero'] = $num;
                $this->reclamation[$i]['date'] = $date;
                $this->reclamation[$i]['remarque'] = (trim($remarque) =='') ? '' : nl2br($remarque);
                $this->reclamation[$i]['etat'] = $etat;
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
    
}

$page = new Reponse();
$page->traiter_requete();