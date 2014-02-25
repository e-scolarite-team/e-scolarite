<?php
require_once("inc/db_engine.php");
require_once("inc/adminbase.php");

class Traiter extends BaseAdmin
{
    public $annee;
    public $etat;
    public function __construct()
    {
        parent::__construct();
        $this->annee='';
        $this->etat='';
    }
    
    public function titre()
    {
        return "Gérer la préinscription";
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
    <h3 class="titre ombre">Gestion de la préinscription</h3>
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
                <p>Cette page est consacrée à la gestion de la préinscription.</p>
                <hr style="border-top: #66CE88 solid 2px;margin:0 18%;"/>
            </div><!-- .detail-contenu -->
            <div class="detail-contenu">
EOC;
            if($this->etat==1)
            {
                $d2 = $this->annee +1;
                echo <<<EOC
                <div class="rdv">
                    <p>La préinscription est actuellement <b>ouverte</b> pour l'année <b>{$this->annee}/{$d2}</b>.</p>
                </div><!-- .rdv -->
EOC;
            }
            else
            {
                echo <<<EOC
                <div class="rdv">
                    <p>La préinscription est actuellement <b>fermée</b>.</p>
                </div><!-- .rdv -->
EOC;
            }
            echo <<<EOC
            <form action="gerer-preinscription-t.php" method="post">
                <fieldset>
                    <legend>Adminstration</legend>
                    <ul>
                    <li>
                        <label>Année : </label>
                        <input type="text" name="annee" />
                    </li>
                    <li>
                        <label>Décision : </label>
                        <select name="etat" required="required">
                            <option value="1">Ouvrir</option>
                            <option value="0">Fermer</option>
                        </select>
                        <input type="submit" style="margin-left:30px;margin-top:10px;" value="Valider" id="valider" />
                    </li>
                    </ul>
                </fieldset>
            </form>
            <fieldset>
                    <legend>Liste des préinscrits</legend>
                    <p><a href="tele-liste-preinscrit.php" >Télécharger</a> la liste des préinscrits de l'année en cours.</p>
                </fieldset>
            </div><!-- .detail-contenu -->
            </div><!-- .class-contenu -->
EOC;
        
    }
    
    public function verification()
    {
        parent::verification();
        $req = "SELECT `annee`,`etat` FROM `gererpreinscription`;";
        db_engine::connecter(SERVER,DB_USER,DB_PASS,DB);
        if($stmt = db_engine::preparer($req))
        {
            $stmt->execute();
            $stmt->store_result();
            $stmt->bind_result($a,$t);
            $i=0;
            if(!$stmt->fetch())
            {
                $this->msgr='Il se peut que le serveur est mal configuré.';
            }
            else
            {
                $this->annee = $a;
                $this->etat = $t;
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