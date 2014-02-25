<?php
require_once("inc/db_engine.php");
require_once("inc/etudiantbase.php");
require_once("inc/etudiant.php");

class Reponse extends BaseEtudiant
{
    public $piece;
    public $etudiant;
    public function __construct()
    {
        parent::__construct();
        $this->piece=array();
    }
    
    public function titre()
    {
        return "Rendez-vous récupération de pièce";
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
    <h3 class="titre ombre">Rendez-vous de récupération de pièce</h3>
EOC;
        
        $nbr = count($this->piece);
        if($nbr==0)
        {
            echo '<p class="detail-contenu">Aucune réponse pour le moment.</p>';
        }
        else
        {
            echo <<<EOC
            <div class="detail-contenu">
                <p>Vous avez <strong>$nbr</strong> rendez-vous.</p>
            </div>
            <hr style="border-top: #66CE88 solid 2px;margin:0 18%;"/>
            <div class="detail-contenu">
EOC;
            foreach($this->piece as $rec)
            {
                echo <<<EOC
                <fieldset>
                <legend>Envoyée Le {$rec['envoi']}</legend>
                    <p>Pièce demandée : {$rec['piece']}</p>
                    <div class="rdv">Rendez-vous le : {$rec['rdv']}</div>
                </fieldset>
EOC;
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
        $req = "SELECT DATE_FORMAT(`d`.`envoi`,GET_FORMAT(DATE,'EUR')),`d`.`rdv`,`p`.`intitule` FROM `demandepiece` AS `d` INNER JOIN `piece` AS `p` ON `d`.`type`=`p`.`numero` WHERE `cne`=?  ORDER BY `d`.`numero` DESC;";
        db_engine::connecter(SERVER,DB_USER,DB_PASS,DB);
        if($stmt = db_engine::preparer($req))
        {
            $stmt->bind_param("s",$this->etudiant->cne);
            $stmt->execute();
            $stmt->store_result();
            $stmt->bind_result($envoi,$rdv,$piece);
            $i=0;
            while($stmt->fetch())
            {
                $this->piece[$i]['envoi'] = $envoi;
                $this->piece[$i]['rdv'] = $rdv;
                $this->piece[$i]['piece'] = $piece;
                $i++;
            }
        }
    }
    
}

$page = new Reponse();
$page->traiter_requete();