<?php

require_once("inc/pagebase.php");
require_once("inc/preinscrit.inc.php");

class Acceuil extends PageBase
{
    public $etat;
    public function __construct()
    {
        parent::__construct();
    }
    
    public function titre()
    {
        return 'Login';
    }
    
    public function emplacementStyles()
    {
        $styles = array();
        $styles[] = "styles/login.css";
        $styles[] = "styles/general.css";
        return $styles;
    }
    
    public function genererContenuBody()
    {
        echo <<<CON
        <div id="header">
			<img height="130px" src="img/logo-fp.png" alt="Faculté polydisciplinaire Sultan Moulay Slimane Beni Mellal" />
		</div><!-- #header -->
<div class="login">
    <h3 class="titre">Préinscription</h3>
CON;
    if($this->etat==1)
        echo "<p>Tapez votre CNE et date naissance pour accéder à la préinscription.</p>";
    
    echo "<div class='formulaire'>";
        
        if($this->etat ==1)
        {
        echo <<<CON
        <form action="preinscription-c-t.php" method="POST">
            <div class="champ">
                <label>CNE</label>
                <input name="cne" required="required" /> <br />
            </div>
            <div class="champ">
                <label>Date naissance</label>
                <input name="date" type="date" required="required"/>
            </div>
CON;
            if($this->msgr!='')
                echo "<div class='erreur'>{$this->msgr}</div>";
            echo <<<CON
            <div class="champ">
                <input type="submit" value="Connexion" />
            </div>
        </form>
CON;
        }
        else
        {
            if($this->msgr!='')
                echo "<div class='erreur'>{$this->msgr}</div>";
        }
        echo <<<CON
    </div>
</div><!-- .login -->
CON;
    }
    
    public function traiter_donnees()
    {
        parent::traiter_donnees();
        if(isset($_GET['err']) and is_numeric($_GET['err']))
        {
            switch($_GET['err'])
            {
                case 104:
                    $this->msgr='Nous sommes désolés, vous ne pouvez pas continuer votre nom ne figure pas parmis notre liste de bacheliers Ou votre mot de passe est incorrecte.';
                    break;
                default:
                    $this->msgr='';
                    break;
            }
        }
    }
    
    public function verification()
    {
        if(is_logged())
        {
            redirectToPage('etudiant.php');
            exit();
        }
        $req = "SELECT `etat` FROM `gererpreinscription` WHERE `id`=1;";
        db_engine::connecter(SERVER,DB_USER,DB_PASS,DB);
        if($stmt = db_engine::preparer($req))
        {
            $stmt->execute();
            $stmt->store_result();
            $stmt->bind_result($etat);
            if($stmt->fetch())
            {
                $this->etat = $etat;
                if($this->etat !=1)
                {
                    $this->msgr="La préinscription est fermée pour le moment.";
                }
            }
            else
            {
                $this->msgr="Nous ne pouvons traiter votre demande veuillez ressayer ultérieurement.";
            }
        }
        else
        {
            $this->msgr="Nous ne pouvons traiter votre demande veuillez ressayer ultérieurement.";
        }
    }
    
}

$page = new Acceuil();
$page->traiter_requete();