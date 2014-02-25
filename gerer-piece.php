<?php
require_once("inc/db_engine.php");
require_once("inc/adminbase.php");

class Traiter extends BaseAdmin
{
    public $piece;
    public function __construct()
    {
        parent::__construct();
        $this->piece = array();
    }
    
    public function titre()
    {
        return "RDV pièces";
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
    <h3 class="titre ombre">RDV pièces</h3>
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
            <div class="detail-contenu">
                <p>Cette page est consacrée à la plannification de récupération de pièces.</p>
                <hr style="border-top: #66CE88 solid 2px;margin:0 18%;"/>
            </div><!-- .detail-contenu -->
            <div class="detail-contenu">

            <form action="gerer-piece-t.php" method="post">
                <fieldset class="gestionrdv">
                    <legend>Rendez-Vous</legend>
                    <ul>
EOC;
            foreach ($this->piece as $p) {
                echo "<li>";
                echo "<label>{$p['value']} : </label>";
                echo "<input type='text' name='{$p['key']}' value='{$p['rdv']}' />";
                echo "</li>";
            }

            echo <<<EOC
                    <li>
                        <input type="submit" style="margin-left:220px;margin-top:10px;" value="Valider" id="valider" />
                    </li>
                    </ul>
                </fieldset>
            </form>
            </div><!-- .detail-contenu -->
            </div><!-- .class-contenu -->
EOC;
        
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
            switch($_GET['i'])
            {
                case 100:
                    $this->msgi="Votre requête a été traitée avec succès";
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
        $req = "SELECT `piece`.`numero`,`piece`.`intitule`,`piece`.`rdv` FROM `piece`;";
        db_engine::connecter(SERVER,DB_USER,DB_PASS,DB);
        if($stmt = db_engine::preparer($req))
        {
            $stmt->execute();
            $stmt->store_result();
            $stmt->bind_result($key,$value,$rdv);
			$i=0;
            while($stmt->fetch())
            {
                $this->piece[$i]['key'] = $key;
				$this->piece[$i]['value'] = $value;
				$this->piece[$i]['rdv'] = (empty($rdv) ) ? '' : $rdv ;
				$i++;
            }
            $stmt->close();
            db_engine::fermer();
        }
    }

}

$page = new Traiter();
$page->traiter_requete();