<?php
require_once("inc/db_engine.php");
require_once("inc/etudiantbase.php");
require_once("inc/etudiant.php");

class Reponse extends BaseEtudiant
{
    public $reclamation;
    public $etudiant;
    public function __construct()
    {
        parent::__construct();
        $this->reclamation=array();
    }
    
    public function titre()
    {
        return "Statut des réclamations de note";
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
    <h3 class="titre ombre">Status des réclamations de notes</h3>
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
                <p>Vous avez <strong>$nbr</strong> réclamations de note.</p>
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
                    <p>Module : Module {$rec['module']}</p>
                    <p>Partie : Partie {$rec['partie']}</p>
                    <p>Note sur le PV : {$rec['pv']}</p>
                    <p>Note réclamée : {$rec['reclame']}</p>
EOC;
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
        $req = "SELECT `r`.`module`,`r`.`partie`,`r`.`pv`,`r`.`reclame`,DATE_FORMAT(`r`.`dateenvoi`,GET_FORMAT(DATE,'EUR')),`r`.`remarque`,`r`.`etat` FROM `reclamationelement` AS `r` WHERE `r`.cne = ? AND NOT isnull(`r`.`etat`) ORDER BY `r`.`numero` DESC;";
        db_engine::connecter(SERVER,DB_USER,DB_PASS,DB);
        if($stmt = db_engine::preparer($req))
        {
            $stmt->bind_param("s",$this->etudiant->cne);
            $stmt->execute();
            $stmt->store_result();
            $stmt->bind_result($mod,$partie,$pv,$rec,$date,$remarque,$etat);
            $i=0;
            while($stmt->fetch())
            {
                $this->reclamation[$i]['module'] = $mod;
                $this->reclamation[$i]['partie'] = $partie;
                $this->reclamation[$i]['pv'] = $pv;
                $this->reclamation[$i]['reclame'] = $rec;
                $this->reclamation[$i]['date'] = $date;
                $this->reclamation[$i]['remarque'] = (trim($remarque) =='') ? '' : nl2br($remarque);
                $this->reclamation[$i]['etat'] = $etat;
                $i++;
            }
        }
    }
    
}

$page = new Reponse();
$page->traiter_requete();