<?php

require_once("inc/db_engine.php");
require_once("inc/etudiantbase.php");
require_once("inc/etudiant.php");

class Acceuil extends BaseEtudiant
{
    private $etudiant;
    private $annee;
    private $etape;
    private $semestre;
    private $filiere;
    
    public function __construct()
    {
        parent::__construct();
    }
    
    public function generer_contenu()
    {
        $a = $this->annee + 1;
        $n = date("d/m/Y",strtotime($this->etudiant->dateNaiss));
        $now = date("d/m/Y");
        echo <<<EOC
        <h3 class="titre ombre">Attestation de scolarité</h3>
        <div class="detail-contenu" >
            <h4 style="margin:25px 0;text-align:center;background-color:#239AFA;padding:5px 0;">ATTESTATION DE SCOLARITE</h4>
        <div style="margin-left:20px;">
            <p style="margin:20px 0;">Le Doyen de la Faculté Polydisciplinaire de Beni Mellal atteste que:</p>
            <p style="margin:20px 0;">L'étudiant (e) : <b>{$this->etudiant->prenom} {$this->etudiant->nom}</b></p>
            <p style="margin:20px 0;">Né (e) le :  <b>{$n}</b> à : <b>{$this->etudiant->ville}</b></p>
            <p style="margin:20px 0;">CNE : <b>{$this->etudiant->cne}</b></p>
            <p style="margin:20px 0;">Est inscrit (e) en : <b>{$this->semestre}</b> {$this->etape}</p>
            <p style="margin:20px 0;">Filière : <b>{$this->filiere}</b></p>
            <p style="margin:20px 0;">Année Universitaire : <b>{$this->annee} / {$a}</b></p>
            <p style="margin:35px 0 0 200px;">Béni Mellal le : {$now}</p>
        </div>
        </div><!-- .detail-contenu -->
        <!-- <a href="telecharger-attestation.php">Télécharger l'attestation.</a>  -->
EOC;

        $html =<<<HTML
        <table border="none">
            <tr>
                <td style="width:250px;">Université Sultan Moulay Slimane<br />Faculté Polydisciplinaire<br />Béni Mellal</td>
                <td style="width:250px;"><img src="img/logo-fp.png" width=200px height=60px /></td>
                <td style="width:250px;text-align:right;">جامعة السلطان مولاي سليمان <h2>الكلية المتعددة الغصائص<br />بني ملال</h2></td>
            </tr>
        </table>
        <h4 style="margin:25px 0;text-align:center;background-color:#239AFA;padding:5px 0;">ATTESTATION DE SCOLARITE</h4>
        <div style="margin-left:20px;">
            <p style="margin:20px 0;">Le Doyen de la Faculté Polydisciplinaire de Beni Mellal atteste que:</p>
            <p style="margin:20px 0;">L'étudiant (e) : <b>{$this->etudiant->prenom} {$this->etudiant->nom}</b></p>
            <p style="margin:20px 0;">Né (e) le :  <b>{$n}</b> à : <b>{$this->etudiant->ville}</b></p>
            <p style="margin:20px 0;">CNE : <b>{$this->etudiant->cne}</b></p>
            <p style="margin:20px 0;">Est inscrit (e) en : <b>{$this->semestre}</b> {$this->etape}</p>
            <p style="margin:20px 0;">Filière : <b>{$this->filiere}</b></p>
            <p style="margin:20px 0;">Année Universitaire : <b>{$this->annee} / {$a}</b></p>
            <p style="margin:35px 0 0 200px;">Béni Mellal le : {$now}</p>
        </div>
HTML;
        
        $_SESSION['attestation'] = $html;

    }
    
    public function verification()
    {
        parent::verification();
        db_engine::connecter(SERVER,DB_USER,DB_PASS,DB);
        $this->etudiant = $_SESSION['etud'];
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

            $req = "SELECT DISTINCT(`e`.`libele2`) FROM `contrat` AS `c` INNER JOIN `elementpedagogique` AS `e` ON `c`.`etape`=`e`.`code` WHERE `c`.`ind`=? AND `c`.`annee`=?;";
            if($stmt = db_engine::preparer($req))
            {
                $stmt->bind_param('si',$this->etudiant->code,$this->annee);
                $stmt->execute();
                $stmt->store_result();
                $stmt->bind_result($etape);
                while($stmt->fetch())
                {
                    $this->etape = $etape;
                }
            }

            $req = "SELECT `e`.`libele1` FROM `contrat` AS `c` INNER JOIN `elementpedagogique` AS `e` ON `c`.`element`=`e`.`code` AND `e`.`type`='sem' WHERE `c`.`ind`=? AND `c`.`annee`=?;";
            if($stmt = db_engine::preparer($req))
            {
                $stmt->bind_param('si',$this->etudiant->code,$this->annee);
                $stmt->execute();
                $stmt->store_result();
                $stmt->bind_result($s);
                while($stmt->fetch())
                {
                    $this->semestre = $this->semestre . $s . ' ';
                }
            }

            $req =<<<SQL
    SELECT DISTINCT(`ee`.`libele2`) 
    FROM `elementpedagogique` AS `ee`
    WHERE `ee`.`code` in
    (
        SELECT `e`.`parent`
        FROM `contrat` AS `c` INNER JOIN `elementpedagogique` AS `e` ON `c`.`etape`=`e`.`code` AND `e`.`type`='etp'
        WHERE `c`.`ind`=? AND `c`.`annee`=?
    );
    
SQL;
            if($stmt = db_engine::preparer($req))
            {
                $stmt->bind_param('si',$this->etudiant->code,$this->annee);
                $stmt->execute();
                $stmt->store_result();
                $stmt->bind_result($f);
                while($stmt->fetch())
                {
                    $this->filiere = $f;
                }
            }


        }        
    }
    
}

$page = new Acceuil();
$page->traiter_requete();