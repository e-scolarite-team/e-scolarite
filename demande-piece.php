<?php

require_once("inc/db_engine.php");
require_once("inc/etudiantbase.php");
require_once("inc/etudiant.php");

class Acceuil extends BaseEtudiant
{    
    public function __construct()
    {
        parent::__construct();
    }
    
    public function generer_contenu()
    {
        echo <<<EOC
    <h3 class="titre ombre">Demande de pièce</h3>
    <div class="detail-contenu" >
    <p>
        Cette page est consacrée pour effectuer une demande de pièce.
    </p>
    <p>
        Pour réaliser la demande commencer par choisir la pièce que vous désirez récupérer auprès du service de la scolarité est valider votre demande.  
    </p>
    </div><!-- .detail-contenu -->
    <hr style="border-top: #66CE88 solid 2px;margin:0 18%;"/>
    <div class="detail-contenu" >
        <form action="demande-piece-t.php" method="post">
        <fieldset>
            <legend>Demander une pièce</legend>
            <div class="champ">
                <label>Type de pièce :</label>
                <select name="piece" required="required">
                    <option></option>
EOC;
        $p = $this->charger_piece();
        foreach($p as $n => $t)
        {
            echo "<option value='$n'>$t</option>";
        }
        
        echo <<<EOC
                </select>
            </div><!-- .champ -->
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
            <div class="champ">
                <input type="submit" value="Envoyer" style="margin:0 0 0 160px;" />
            </div><!-- .champ -->
        </fieldset>
        </form>
    </div><!-- .detail-contenu -->
EOC;
    }
    
    public function emplacementStyles()
    {
        $styles = parent::emplacementStyles();
        $styles[] = "styles/formulaire.css";
        return $styles;
    }
    
    public function traiter_donnees()
    {
        parent::traiter_donnees();
        if(isset($_GET['err']) and is_numeric($_GET['err']))
        {
            switch($_GET['err'])
            {
                case 104:
                    $this->msgr='Un probleme est survenu pendant le traitement de votre demande, veuillez ressayer.';
                    break;
                case 105:
                    $this->msgr='Erreur de configuration du serveur, veuillez ressayer plus tard.';
                    break;
                case 106:
                    $this->msgr='Vous devez spécifier le type de pièce.';
                    break;
				case 107:
                    $this->msgr='Vous ne pouvez pas prendre un rendez-vous pour cette pièce pour le moment,ressayez plus tard.';
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
                    $this->msgi='Votre demande a été envoyée avec succès.<br />';
                    db_engine::connecter(SERVER,DB_USER,DB_PASS,DB);
                    $req = "SELECT `piece`.`rdv`,`piece`.`intitule` FROM `piece` WHERE `piece`.`numero`=? ;";
                    if($stmt = db_engine::preparer($req))
                    {
                        $stmt->bind_param("i",$_GET['n']);
                        $stmt->execute();
                        $stmt->bind_result($rdv,$intitule);
                        if($stmt->fetch())
                        {
                            $this->msgi .= "Rendez-Vous Le <b>$rdv</b> pour récupérer '<b>$intitule</b>'.";
                        }
                        $stmt->close();
                        db_engine::fermer();
                    }else
                    {
                        $this->msgr='Erreur lors du chargement de la page, veuillez ressayer plus tard.';
                    }
                    break;
                default:
                    $this->msgi='';
                    break;
            }
        }
    }
    
    private function charger_piece()
    {
        $p = array();
        db_engine::connecter(SERVER,DB_USER,DB_PASS,DB);
        $req = "SELECT `piece`.`numero`,`piece`.`intitule` FROM `piece`;";
        if($stmt = db_engine::preparer($req))
        {
            $stmt->execute();
            $stmt->bind_result($num,$intitule);
            while($stmt->fetch())
            {
                $p[$num] = $intitule;
            }
            $stmt->close();
            db_engine::fermer();
        }else
        {
            $this->msgr='Erreur lors du chargement de la page, veuillez ressayer plus tard.';
        }
        return $p;
    }
    
}

$page = new Acceuil();
$page->traiter_requete();