<?php

require_once("inc/pagebase.php");
require_once("inc/db_engine.php");

class Acceuil extends PageBase
{
    public function __construct()
    {
        parent::__construct();
    }
    
    public function titre()
    {
        return 'Acceuil';
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
    <h3 class="titre">Service de scolarité</h3>
    <div class="formulaire">
        <form action="gestion.php" method="POST">
            <div class="champ">
                <label>Adminstareur</label>
                <input name="user" required="required" /> <br />
            </div>
            <div class="champ">
                <label>Mot de passe</label>
                <input name="password" type="password" required="required"/>
            </div>
            <div class="champ">
            <input type="submit" value="Connexion" />
            </div>
        </form>
    </div>
CON;
        if($this->msgr!='')
        {
            echo '<div class="erreur">' . $this->msgr . '</div>';
        }
        echo <<<CON
</div><!-- .login -->


CON;
    }
    
    public function traiter_donnees()
    {
        if($_SERVER["REQUEST_METHOD"] == "POST" and !empty($_POST['user']) and !empty($_POST['password']))
        {
            $req = "SELECT username,`password` FROM superuser WHERE username=? AND password=?";
            db_engine::connecter(SERVER,DB_USER,DB_PASS,DB);
            if($stmt= db_engine::preparer($req))
            {
                $pass = md5($_POST['password']);
                $stmt->bind_param("ss",$_POST['user'],$pass);
                $stmt->execute();
                $stmt->store_result();
                $nb = $stmt->num_rows;
                if($nb>0)
                {
                    $_SESSION['admin'] = htmlspecialchars($_POST['username']);
                    redirectToPage('traiter-module.php');
                    exit();
                }
                else
                {
                    $this->msgr = 'User ou mot de passe incorrecte';
                }
            }

        }
    }
    
    public function verification()
    {
        if(is_loggedAdmin())
        {
            redirectToPage('traiter-module.php');
            exit();
        }
    }
    
}

$page = new Acceuil();
$page->traiter_requete();