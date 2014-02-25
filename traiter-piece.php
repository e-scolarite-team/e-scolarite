<?php
require_once("inc/db_engine.php");
require_once("inc/adminbase.php");

class Traiter extends BaseAdmin
{
    public $piece;
    public function __construct()
    {
        parent::__construct();
        $this->piece=array();
    }
    
    public function titre()
    {
        return "Traiter les demandes de pièces";
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
    <h3 class="titre ombre">Traiter les demandes de pièces</h3>
EOC;
        if($this->msgi!='')
        {
            echo '<div class="info">' . $this->msgi . '</div>';
        }
        if($this->msgr!='')
        {
            echo '<div class="erreur">' . $this->msgr . '</div>';
        }
        $nbr = count($this->piece);
        if($nbr==0)
        {
            echo '<p class="detail-contenu">Aucune demande à traiter.</p>';
        }
        else
        {
            echo <<<EOC
            <div class="detail-contenu">
                <p>Il y a <strong>$nbr</strong> demandes de pièce non traitées.</p>
            </div>
            <hr style="border-top: #66CE88 solid 2px;margin:0 18%;"/>
            <div class="detail-contenu">
EOC;
            foreach($this->piece as $rec)
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
                        <label>Pièce demandée : </label><span>{$rec['piece']}</span>
                    </li>
                    </div><!-- .reclamation-detail -->
                <div class="reclamation-detail">
                    <li style="font-weight:600;font-size:14px;;line-height:50px;">
                        <label>Rendez-Vous : </label><span>{$rec['rdv']}</span>
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
        
    public function verification()
    {
        parent::verification();
        $req = "SELECT `d`.`cne`,DATE_FORMAT(`d`.`envoi`,GET_FORMAT(DATE,'EUR')),`d`.`rdv`,`et`.`nom`,`et`.`prenom`,`p`.`intitule` FROM `demandepiece` AS `d` INNER JOIN `etudiant` AS `et` ON `d`.`cne`=`et`.`cne` INNER JOIN `piece` AS `p` ON `d`.`type`=`p`.`numero` ORDER BY `d`.`envoi` DESC LIMIT 0,500";
        db_engine::connecter(SERVER,DB_USER,DB_PASS,DB);
        if($stmt = db_engine::preparer($req))
        {
            $stmt->execute();
            $stmt->store_result();
            $stmt->bind_result($cne,$date,$rdv,$nom,$prenom,$piece);
            $i=0;
            while($stmt->fetch())
            {
                $this->piece[$i]['cne'] = $cne;
                $this->piece[$i]['date'] = $date;
                $this->piece[$i]['rdv'] = $rdv;
                $this->piece[$i]['nom'] = $nom;
                $this->piece[$i]['prenom'] = $prenom;
                $this->piece[$i]['piece'] = $piece;
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
                    $this->msgi="<strong>$nb</strong> demandes ont été traitées avec succès.";
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