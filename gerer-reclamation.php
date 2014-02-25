<?php
require_once("inc/db_engine.php");
require_once("inc/adminbase.php");

class Traiter extends BaseAdmin
{
    public $sess;
    public function __construct()
    {
        parent::__construct();
        $this->sess =array();
    }
    
    public function titre()
    {
        return "Gérer les réclamations";
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
    <h3 class="titre ombre">Gestion des reclamations</h3>
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
                <p>Cette page est consacrée à la gestion des sessions de réclamations.</p>
                <hr style="border-top: #66CE88 solid 2px;margin:0 18%;"/>
            </div><!-- .detail-contenu -->
            <div class="detail-contenu">
EOC;
            foreach($this->sess as $s)
            {
                $t = ($s['etat'] ==1) ? 'Activées' : 'Désactivées';
                echo <<<EOC
                <div class="rdv">
                    <p>Les réclamations de la <strong>{$s['intitule']}</strong> sont <strong>{$t}</strong>.</p>
                </div><!-- .rdv -->
EOC;
            }
            
            echo <<<EOC
                <fieldset>
                    <legend>Adminstration</legend>
                    <ul>
EOC;
            foreach($this->sess as $s)
            {
                echo <<<EOC
                        <li>
                            <form action="gerer-reclamation-t.php" method="post">
                            <label>{$s['intitule']} : </label>
                            <select name="etat" required="required"  >
                                <option value="1">Ouvrir</option>
                                <option value="0">Fermer</option>
                            </select>
                            <input type='hidden' name="sid" value="{$s['sid']}" />
                            <input type="submit" style="margin-left:10px;" value="Valider" id="valider" />
                            </form>
                        </li>
EOC;
            }
            echo <<<EOC
                    </ul>
                </fieldset>
            </div><!-- .detail-contenu -->
            </div><!-- .class-contenu -->
EOC;
        
    }
    
    public function verification()
    {
        parent::verification();
        $req = "SELECT `sid`,`etat`,`intitule` FROM `session_module`;";
        db_engine::connecter(SERVER,DB_USER,DB_PASS,DB);
        if($stmt = db_engine::preparer($req))
        {
            $stmt->execute();
            $stmt->store_result();
            $stmt->bind_result($sid,$t,$intitule);
            $i = 0;
            while($stmt->fetch())
            {
                $this->sess[$i]['sid'] = $sid;
                $this->sess[$i]['etat'] = $t;
                $this->sess[$i]['intitule'] = $intitule;
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
                    $this->msgi="Votre requête a été traitée avec succès";
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