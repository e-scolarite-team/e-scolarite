<?php
require_once("inc/db_engine.php");
class Etudiant
{
    public $cne;
    public $code;
    public $nom;
    public $prenom;
    public $sexe;
    public $cin;
    public $dateNaiss;
    public $ville;
    
    public function __construct(){}
    
    public static function fetchEtudiantByCNE($cne_p)
    {
        db_engine::connecter(SERVER,DB_USER,DB_PASS,DB);
        $req = "SELECT `etudiant`.`cne`,`etudiant`.`codeind`,`etudiant`.`nom`,`etudiant`.`prenom`,DATE_FORMAT(`etudiant`.`datenaissance`,`etudiant`.`cin`,`etudiant`.`sexe` FROM `etudiant` WHERE `etudiant`.`cne`=?;";
        if($stmt = db_engine::preparer($req))
        {
            $stmt->bind_param("s",$cne_p);
            $stmt->execute();
            $stmt->store_result();
            $stmt->bind_result($cne_r,$ind_r,$nom_r,$prenom_r,$date_r,$cin_r,$sexe_r);
            $stmt->close();
            db_engine::fermer();
            if($stmt->fetch())
            {
                $et = new Etudiant();
                $et->cne = $cne_r;
                $et->code = $ind_r;
                $et->nom = $nom_r;
                $et->prenom = $prenom_r;
                $et->dateNaiss = $date_r;
                $et->cin = $cin_r;
                $et->sexe = $sexe_r;
                return $et;
            }
            else
            {
                return false;
            }
        }
        return false;
    }
    
    public static function authEtudiant($cne_p,$date_p)
    {
        db_engine::connecter(SERVER,DB_USER,DB_PASS,DB);
        $req = "SELECT `etudiant`.`cne`,`etudiant`.`codeind`,`etudiant`.`nom`,`etudiant`.`prenom`,DATE_FORMAT(`etudiant`.`datenaissance`,GET_FORMAT(DATE,'EUR')),`etudiant`.`cin`,`etudiant`.`sexe`,`etudiant`.`villenaissance` FROM `etudiant` WHERE `etudiant`.`cne`=? AND `etudiant`.`datenaissance`=?;";
        if($stmt = db_engine::preparer($req))
        {
            $stmt->bind_param("ss",$cne_p,$date_p);
            $stmt->execute();
            
            $stmt->bind_result($cne_r,$ind_r,$nom_r,$prenom_r,$date_r,$cin_r,$sexe_r,$ville_r);
            
            if($stmt->fetch())
            {
                $et = new Etudiant();
                $et->cne = $cne_r;
                $et->code = $ind_r;
                $et->nom = $nom_r;
                $et->prenom = $prenom_r;
                $et->dateNaiss = $date_r;
                $et->cin = $cin_r;
                $et->sexe = $sexe_r;
                $et->ville = $ville_r;
                $stmt->close();
                db_engine::fermer();
                return $et;
            }
            $stmt->close();
            db_engine::fermer();
        }
        return false;
    }
    
}
