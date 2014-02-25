<?php

require_once("inc/db_engine.php");
require_once("inc/etudiantbase.php");
require_once("inc/etudiant.php");

class Acceuil extends BaseEtudiant
{    
    public $mods;
    
    public function __construct()
    {
        parent::__construct();
        $this->mods = array();
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
    <h3 class="titre ombre">Réclamation de changement de module</h3>
    <div class="detail-contenu" >
    <p>
        Cette page est consacrée pour effectuer une réclamation de changement de module.
    </p>
    <p>
        Pour réaliser la demande commencer par choisir les modules programmés puis les module que vous réclamez ensuite cliquer sur envoyer.  
    </p>
    </div><!-- .detail-contenu -->
    <hr style="border-top: #66CE88 solid 2px;margin:0 18%;"/>
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
    <div class="detail-contenu" >
        <form action="reclamation-changement-t.php" method="post">
        <fieldset>
            <legend>Modules programmés</legend>
EOC;
            $i=0;
            foreach($this->mods as $m)
            {
                echo "<input type='checkbox' name=a[$m] value='$m' class='mods' />$m";
                $i++;
                if($i%4==0)
                {
                    echo "<br />";
                }
            }
            
            echo "</fieldset>";
            echo "<fieldset><legend>Modules réclamées</legend>";
            $i=0;
            foreach($this->mods as $m)
            {
                echo "<input type='checkbox' name=r[$m] value='$m' class='mods' />$m";
                $i++;
                if($i%4==0)
                {
                    echo "<br />";
                }
            }
            echo "</fieldset>";
        
        echo <<<EOC
        <label>Pour valider votre demande cliquer sur ce bouton : </label><input type="submit" value="Valider" id="valider" style="margin:5px 10px;" />
        </form>
    </div><!-- .detail-contenu -->
EOC;
    }
    
    public function verification()
    {
        parent::verification();
        $req = "SELECT `modules`.`mid` FROM `modules` ORDER BY `modules`.`mid`;";
        db_engine::connecter(SERVER,DB_USER,DB_PASS,DB);
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
                    $this->msgr='Veuillez choisir les modules.';
                    break;
                case 105:
                    $this->msgr='Erreur de configuration du serveur, veuillez ressayer plus tard.';
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
                    $this->msgi='Votre réclamation a été envoyée avec succès.';
                    break;
                default:
                    $this->msgi='';
                    break;
            }
        }
    }
    
}

$page = new Acceuil();
$page->traiter_requete();