<?php

require_once("inc/pagebase.php");
require_once("inc/preinscrit.inc.php");

class Acceuil extends PageBase
{
    private $filiere;
    private $annee;
    public function __construct()
    {
        parent::__construct();
        $this->filiere = array();
    }
    
    public function titre()
    {
        return 'Preinscription';
    }
    
    public function emplacementStyles()
    {
        $styles = array();
        $styles[] = "styles/general.css";
        $styles[] = "styles/preinscription.css";
        return $styles;
    }
    
    public function genererContenuBody()
    {
        $d = $this->annee +1;
        echo <<<CON
        <div id="header">
			<img height="130px" src="img/logo-fp.png" alt="Faculté polydisciplinaire Sultan Moulay Slimane Beni Mellal" />
		</div><!-- #header -->
        <div class="pr-conteneur">
            <div class="info-right">جامعة السلطان مولاي سليمان<h2>الكلية المتعددة التخصصات<br />بني ملال</h2></div>
            <div class="info-left">Université Sultan Moulay Slimane<h2>Faculté Polydisciplinaire<br />Béni Mellal</h2></div>
            <h3 style="font-size:0.95em;font-weight:100;margin:0;">Année universitaire: {$this->annee}-{$d}</h3>
            <h3>Préinscription</h3>
CON;
        if(count($this->filiere)==0)
        {
            echo "<div class='erreur'>Veuillez essayer ultérieurement un problème est survenue.</div>";
        }
        else
        {
            $pr = $_SESSION['pr'];
            echo <<<EOF
            <form action="preinscription-t.php" method="POST" >
            <input type="hidden" name="a" value="{$this->annee}" />
            <fieldset>
            <legend>Choix de la filière</legend>
            <label>Filière : </label>
            <select name='filiere' required='required'><option></option>        
EOF;
            foreach($this->filiere as $code => $fil)
            {
                echo "<option value='$code'>$fil</option>";
            }
        echo <<<CON
            </select>
            </fieldset>
            <fieldset>
                <legend>Etat civil - {$pr->nomprenom}</legend>
                <ul>
                <div class="champ-ar">
                    <li>
                        <label>الاسم العائلي : </label>
                        <input type="text" name="nomar" required="required" />
                    </li>
                    <li>
                        <label>الاسم الشخصي : </label>
                        <input type="text" name="prenomar" required="required" />
                    </li>
                    <li>
                        <label>رقم البطاقة الوطنية (CIN) : </label>
                        <input type="text" name="cin" required="required" />
                    </li>
                    <li>
                        <label>مكان الازدياد : </label>
                        <input type="text" name="lnaissar" required="required" />
                    </li>
                </div>
                <li>
                    <label>CNE : </label>
                    <input type="text" name="cne" value="{$pr->cne}" readonly="readonly" />
                </li>
                <li>
                    <label>Nom : </label>
                    <input type="text" name="nom" required="required" />
                </li>
                <li>
                    <label>Prénom : </label>
                    <input type="text" name="prenom" required="required" />
                </li>
                <li>
                    <label>Date de naissance : </label>
                    <input type="text" name="datenaiss" disabled="disabled" readonly="readonly" value="{$pr->dateNaiss}" />
                </li>
                <li>
                    <label>Lieu de naissance : </label>
                    <input type="text" name="lnaiss" required="required" />
                </li>
                <li>
                    <label>Situation familiale : </label>
                    <input type="text" name="sfam" required="required" />
                </li>
                <li>
                    <label>Province de naissance : </label>
                    <input type="text" name="provnaiss" required="required" />
                </li>
                <li>
                    <label>Pays : </label>
                    <input type="text" name="pays" required="required" />
                </li>
                <li>
                    <label>Sexe : </label>
                    <select name="sexe" required="required">
                        <option></option>
                        <option value="M">M</option>
                        <option value="F">F</option>
                    </select>
                </li>
                <li>
                    <label>Adresse Personnel : </label>
                    <textarea name="adrperso"></textarea>
                </li>
                <li>
                    <label>Ville : </label>
                    <input type="text" name="ville" required="required" />
                </li>
                <li>
                    <label>Téléphone personnel : </label>
                    <input type="text" name="telephone" />
                </li>
                <li>
                    <label>E-mail : </label>
                    <input type="text" name="email" />
                </li>
                <li>
                    <label>Nationalité : </label>
                    <input type="text" name="nationalite" required="required" />
                </li>
                <li>
                    <label>Passeport : </label>
                    <input type="text"  name="passeport" />
                </li>
                </ul>
            </fieldset>
            <fieldset>
                <legend>Baccalauréat</legend>
                <ul>
                <li>
                    <label>Année d'obtention du bac : </label>
                    <input type="text" name="anneebac" required="required" />
                </li>
                <li>
                    <label>Série du bac : </label>
                    {$pr->seriebac}
                </li>
                <li>
                    <label>Lycée : </label>
                    <input type="text" name="lycee" />
                </li>
                <li>
                    <label>Province : </label>
                    {$pr->province}
                </li>
                <li>
                    <label>Mention du bac : </label>
                    <select name="mention" required="required">
                        <option></option>
                        <option value="P">Passable</option>
                        <option value="AB">Assez bien</option>
                        <option value="B">Bien</option>
                        <option value="TB">Très bien</option>
                    </select>
                </li>
                <li>
                    <label>Académie : </label>
                    {$pr->academie}
                </li>
                </ul>
            </fieldset>
            <fieldset>
                <legend>Parents</legend>
                <ul>
                <li>
                    <label>Nom et Prénom du père : </label>
                    <input type="text" name="nompere" />
                </li>
                <li>
                    <label>Nom et Prénom de la mère : </label>
                    <input type="text"  name="nommere" />
                </li>
                <li>
                    <label>Nom et Prénom du tuteur : </label>
                    <input type="text"  name="nomtuteur" />
                </li>
                <li>
                    <label>Adresse actuelle des parents : </label>
                    <input type="text" name="adrparent" />
                </li>
                <li>
                    <label>Commune : </label>
                    <input type="text" name="commune" />
                </li>
                <li>
                    <label>Province ou préfecture : </label>
                    <input type="text" name="provparent" />
                </li>
                <li>
                    <label>Téléphone : </label>
                    <input type="text" name="telparent" />
                </li>
                <li>
                    <label>Groupe socioprofessionel:</label>
                </li>
                <li class="fix">
                    <label>Père : </label>
                    <input type="text" name="profpere" />
                </li>
                <li>
                    <label>Mère : </label>
                    <input type="text" name="profmere" />
                </li>
                </ul>
            </fieldset>
            <fieldset>
                <legend>Informations Complémentaires</legend>
                <ul>
                <li>
                    <label>Fonction (si vous travaillez) : </label>
                    <input type="text" name="fonction" />
                </li>
                <li>
                    <label>Organisme employeur : </label>
                    <input type="text" name="employeur" />
                </li>
                <li>
                    <label>Handicapé(e) : </label>
                    <select name="handicap" required="required">
                        <option></option>
                        <option value="1">Oui</option>
                        <option value="0">Non</option>
                    </select>
                </li>
                <li>
                    <label>Nature d'handicap : </label>
                    <input type="text" name="natureh" />
                </li>
                </ul>
            </fieldset>
            <input type="submit" name="valider" value="Valider" />
            </form>
        </div><!-- .pr-conteneur -->
CON;

        }
    }
    
    public function traiter_donnees()
    {
    }
    
    public function verification()
    {
        parent::verification();
        if(is_logged())
        {
            redirectToPage('etudiant.php');
            exit();
        }
        if(!isset($_SESSION['pcne']) or !isset($_SESSION['pr']))
        {
            redirectToPage('preinscription-c.php');
            exit();
        }
        $req = "SELECT `elementpedagogique`.`code`,`elementpedagogique`.`libele2` FROM `elementpedagogique` WHERE `type`='fil';";
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