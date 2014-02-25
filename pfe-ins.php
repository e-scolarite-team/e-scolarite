<?php

require_once("inc/db_engine.php");
require_once("inc/etudiantbase.php");
require_once("inc/etudiant.php");

class Acceuil extends BaseEtudiant
{    
    private $annee;
    private $filiere;
    public function __construct()
    {
        parent::__construct();
    }
    
    public function generer_contenu()
    {
        $et = $_SESSION['etud'];
        echo <<<EOC
    <h3 class="titre ombre">Projet de Fin d'études (PFE)</h3>
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
        <form action="pfe-ins-t.php" method="post">
        <fieldset>
        <legend>Fiche du Projet de Fin d'études (PFE)</legend>
        <div style="font-size:10px;">
            <div class="champ">
                <label>Intitule du projet :</label>
                <input type="text" name="intitule" />
            </div><!-- .champ -->
            <div class="champ">
                <label>Nom de l'enseignant encadrant :</label>
                <input type="text" name="encadrant" />
            </div><!-- .champ -->
            <input type="hidden" value="{$this->annee}" name="annee" />
            <div class="champ">
                <label>Filiere :</label>
                <select name="filiere" required="required">
                    <option></option>
EOC;
        foreach($this->filiere as $code => $fil)
            {
                echo "<option value='$code'>$fil</option>";
            }
        echo <<<EOC
                </select>
            </div><!-- .champ -->
            <fieldset>
            <legend>Etudiant 1</legend>
                <div class="champ">
                    <label>Nom et Prénom :</label>
                    <input type="text" name="e1[nom]" value="{$et->nom} {$et->prenom}" />
                </div><!-- .champ -->
                <div class="champ">
                    <label>CNE :</label>
                    <input type="text" name="e1[cne]" value="{$et->cne}" />
                </div><!-- .champ -->
                <div class="champ">
                <label>Téléphone :</label>
                <input type="text" name="e1[tel]" />
                </div><!-- .champ -->
                <div class="champ">
                    <label>E-mail :</label>
                    <input type="text" name="e1[email]" />
                </div><!-- .champ -->
            </fieldset>
            <fieldset>
            <legend>Etudiant 2</legend>
                <div class="champ">
                    <label>Nom et Prénom :</label>
                    <input type="text" name="e2[nom]" />
                </div><!-- .champ -->
                <div class="champ">
                    <label>CNE :</label>
                    <input type="text" name="e2[cne]" />
                </div><!-- .champ -->
                <div class="champ">
                <label>Téléphone :</label>
                <input type="text" name="e2[tel]" />
                </div><!-- .champ -->
                <div class="champ">
                    <label>E-mail :</label>
                    <input type="text" name="e2[email]" />
                </div><!-- .champ -->
            </fieldset>
            <fieldset>
            <legend>Etudiant 3</legend>
                <div class="champ">
                    <label>Nom et Prénom :</label>
                    <input type="text" name="e3[nom]" />
                </div><!-- .champ -->
                <div class="champ">
                    <label>CNE :</label>
                    <input type="text" name="e3[cne]" />
                </div><!-- .champ -->
                <div class="champ">
                <label>Téléphone :</label>
                <input type="text" name="e3[tel]" />
                </div><!-- .champ -->
                <div class="champ">
                    <label>E-mail :</label>
                    <input type="text" name="e3[email]" />
                </div><!-- .champ -->
            </fieldset>
EOC;
        
        echo <<<EOC
            <div class="champ">
                <input type="submit" value="Valider" style="margin:0 0 0 210px;" />
            </div><!-- .champ -->
        </fieldset>
        </form>
    </div>
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

    public function verification()
    {
        parent::verification();
        
        $req = "SELECT `elementpedagogique`.`code`,`elementpedagogique`.`libele1` FROM `elementpedagogique` WHERE `type`='fil';";
        db_engine::connecter(SERVER,DB_USER,DB_PASS,DB);
        if($stmt = db_engine::preparer($req))
        {
            $stmt->execute();
            $stmt->store_result();
            $stmt->bind_result($code,$fil);
            while($stmt->fetch())
            {
                $this->filiere[$code] = $fil;
            }
        }
        $req = "SELECT `annee` FROM `gererpreinscription` WHERE `id`=1;";
        if($stmt = db_engine::preparer($req))
        {
            $stmt->execute();
            $stmt->store_result();
            $stmt->bind_result($a);
            while($stmt->fetch())
            {
                $this->annee = $a;
            }
        }
    }
    
}

$page = new Acceuil();
$page->traiter_requete();