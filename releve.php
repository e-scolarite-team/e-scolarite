<?php

require_once("inc/db_engine.php");
require_once("inc/etudiantbase.php");
require_once("inc/etudiant.php");

class Releve extends BaseEtudiant
{
    private $releve;
    private $etudiant;
    private $filiere;
    
    public function __construct()
    {
        parent::__construct();
    }
    
    public function generer_contenu()
    {
        echo <<<EOC
    <h3 class="titre ombre">Relevé de notes</h3>
    <div class="detail-contenu" >
    <p>
        Pour consulter les notes choisissez le semestre et valider.  
    </p>
    </div><!-- .detail-contenu -->
    <hr style="border-top: #66CE88 solid 2px;margin:0 18%;"/>
    <div class="detail-contenu" >
        <form action="releve.php" method="post">
        <fieldset>
            <legend>Choix du semestre</legend>
            <label>Filière : </label>
                <select name='filiere' required='required'><option></option>        
EOC;
            foreach($this->filiere as $code => $fil)
            {
                echo "<option value='$code'>$fil</option>";
            }
            echo <<<EOC
                </select>
                <label>Semestre :</label>
                <select name="semestre" required="required">
                    <option></option>
EOC;
        for($i=1;$i<=6;$i++)
        {
            echo "<option value=\"S$i\">Semestre $i</option>";
        }
        echo "</select>";
            
        if($this->msgi!='')
        {
            echo '<div class="info">' . $this->msgi . '</div>';
        }
        if($this->msgr!='')
        {
            echo '<div class="erreur">' . $this->msgr . '</div>';
        }
        echo <<<EOC
                <input type="submit" value="Valider" style="margin:0 0 0 10px;" />
            </fieldset>
        </form>
    
EOC;
        
        if(!empty($this->releve))
        {
            echo $this->releve;
            $html =<<<HTML
            <html>
            <head>
                <style type="text/css">
                    .gras
                    {
                        font-weight: bold;
                    }
                    td
                    {
                        padding: 8px 12px;
                    }
                </style>
            </head>
            <body>
            <table border="none">
            <tr>
                <td style="width:250px;">Université Sultan Moulay Slimane<br />Faculté Polydisciplinaire<br />Béni Mellal</td>
                <td style="width:250px;"><img src="img/logo-fp.png" width=200px height=60px /></td>
                <td style="width:250px;text-align:right;">جامعة السلطان مولاي سليمان <h2>الكلية المتعددة الغصائص<br />بني ملال</h2></td>
            </tr>
            </table>
HTML;
            $html .= $this->releve ;
            $html .= "</body></html>";
            $_SESSION['releve'] = $html;
        }
        echo "</div><!-- .detail-contenu -->";
        if(!empty($this->releve))
        {
            echo "<a href='telecharger-releve.php'>Télécharger le relevé (PDF).</a>";
        }
    }
    
    public function afficher_releve()
    {
        $s = $_POST["semestre"];
        $rel=array();
        $req = "SELECT `n`.`element`,COALESCE(`n`.`note`,'') AS `note`,COALESCE(`n`.`session`) AS `session`,COALESCE(`n`.`etat`,'') AS `etat`,`n`.`annee`,`e`.`libele1`,`e`.`libele2`,`e`.`type`,`e`.`parent`,`p`.`libele1` FROM `elementpedagogique` AS `e` LEFT OUTER JOIN `notes` AS `n` ON `n`.`element` = `e`.`code` LEFT JOIN `etudiant` AS `et` ON `et`.`codeind`=`n`.`ind` LEFT JOIN (SELECT `code`,`libele1` FROM `elementpedagogique`) AS `p` ON  `p`.`code` = `e`.`parent` ORDER BY `e`.`code` ASC;";
        db_engine::connecter(SERVER,DB_USER,DB_PASS,DB);
        if($stmt = db_engine::preparer($req))
        {
            if(!$stmt->execute())
            {
                redirectToPage("releve.php?err=105");
                exit();
            }
            $stmt->bind_result($elem,$note,$session,$etat,$annee,$lib1,$lib2,$type,$parent,$libparent);
            while($stmt->fetch())
            {
                if($type=="sem" and $lib1 ==$s and $parent==$_POST['filiere'])
                {
                    $rel[$elem]["lib1"] = $lib1;
                    $rel[$elem]["lib2"] = $lib2;
                    $rel[$elem]["note"] = $note;
                    $rel[$elem]["annee"] = $annee;
                    $rel[$elem]["session"] = ($session==1)? "Ordinaire" : "Rattrapage";
                    $rel[$elem]["parent"] = $libparent;
                }
                if($type=="mod" and isset($rel[$parent]))
                {
                    $rel[$parent]["mod"][$elem]["lib1"] = $lib1;
                    $rel[$parent]["mod"][$elem]["lib2"] = $lib2;
                    $rel[$parent]["mod"][$elem]["note"] = $note;
                    $rel[$parent]["mod"][$elem]["annee"] = $annee;
                    $rel[$parent]["mod"][$elem]["session"] = ($session==1)? "Ordinaire" : "Rattrapage";
                }
                if($type=="elm" )
                {
                    foreach($rel as &$ksem)
                    {    
                        foreach($ksem as &$rsem)
                        {
                            if(is_array($rsem))
                            {
                                foreach($rsem as $kmod => &$rmod)
                                {
                                    if($parent == $kmod)
                                    {
                                        $rmod['elem'][$elem]["lib1"] = $lib1;
                                        $rmod['elem'][$elem]["lib2"] = $lib2;
                                        $rmod['elem'][$elem]["note"] = $note;
                                        $rmod['elem'][$elem]["annee"] = $annee;
                                        $rmod['elem'][$elem]["session"] = ($session==1)? "Ordinaire" : "Rattrapage";
                                    }
                                }
                            }
                            
                        }
                    }
                    
                }
            }
            $stmt->close();
        }
        db_engine::fermer();
        $this->etudiant = $_SESSION['etud'];
        $this->releve = <<<EOC
    <p>Le doyen de la faculté polydisciplinaire Beni Mellal, certifie que :</p>
    <p>Nom         : {$this->etudiant->nom}</p>
    <p>Prénom         : {$this->etudiant->prenom}</p>
    <p>CNE         : {$this->etudiant->cne}</p>
    <p>Filière         : {$this->filiere[$_POST['filiere']]}</p>
    <p>A obtenu les notes suivantes :</p>
    <table border="1" >
    	<tr><th colspan="2">Modules</th><th>Note/20</th><th>Moyenne</th><th>Année</th><th>Session</th></tr>
EOC;

        foreach($rel as $ksem)
        {
            if(is_array($ksem))
            {
                foreach($ksem as $sem)
                {
                    if(is_array($sem))
                    {
                        foreach($sem as $kmod)
                        {
                            if(is_array($kmod))
                            {
                                $this->releve .= "<tr><td rowspan=\"3\" class='gras'>{$kmod['lib1']}</td><td colspan=\"2\" class='gras'>{$kmod['lib2']}</td><td rowspan=\"3\">{$kmod['note']}</td><td rowspan=\"3\">{$kmod['annee']}</td><td rowspan=\"3\">{$kmod['session']}</td></tr>";
                                foreach($kmod as $mod)
                                {
                                    if(is_array($mod))
                                    {
                                        foreach($mod as $kpartie)
                                        {
                                            $this->releve .= "<tr><td>{$kpartie['lib2']}</td><td>{$kpartie['note']}</td></tr>";
                                        }
                                    }
                                }
                            }                            
                        }
                    }
                }
            }
            $this->releve .= "<tr><td colspan=\"3\" class='gras'>Résultat Du {$ksem['lib2']}</td><td>{$ksem['note']}</td><td>{$ksem['annee']}</td><td>{$ksem['session']}</td></tr>";
        }
        $this->releve .="</table>";
    }
    
    public function traiter_donnees()
    {
        parent::traiter_donnees();
        if( !empty($_POST['semestre']) and !empty($_POST['filiere']) )
        {
            $this->afficher_releve();
        }
        else
        {
            $this->msgr = "Vous devez choisir le semestre et la filière pour afficher les notes.";
        }
        
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
                    $this->msgi='';
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
    }
    
    public function emplacementStyles()
    {
        $st = parent::emplacementStyles();
        $st[] ="styles/releve.css";
        return $st;
    }
    
}

$page = new Releve();
$page->traiter_requete();