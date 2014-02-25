<?php
require_once("inc/db_engine.php");
class Preinscrit
{
    public $cne;
    public $nomprenom;
    public $nomprenomar;
    public $dateNaiss;
    public $seriebac;
    public $province;
    public $academie;

    public function __construct(){}
    
    public static function authPreinscrit($cne_p,$date_p)
    {
        db_engine::connecter(SERVER,DB_USER,DB_PASS,DB);
        $req = "SELECT `b`.`cne`,`b`.`nom`,`b`.`date_naissance`,`a`.`academie`,`s`.`serie`,`p`.`province` FROM `bacglob` AS `b` INNER JOIN `academies` AS `a` ON `b`.`academie` = `a`.`code` INNER JOIN `series` AS `s` ON `b`.`serie`=`s`.`code` INNER JOIN `provinces` AS `p` ON `b`.`provetab`=`p`.`code` WHERE `b`.`cne`=? AND `b`.`date_naissance`=?;";
        if($stmt = db_engine::preparer($req))
        {
            $stmt->bind_param("ss",$cne_p,$date_p);
            $stmt->execute();
            
            $stmt->bind_result($cne_r,$nom_r,$date_r,$academie_r,$serie_r,$province_r);
            
            if($stmt->fetch())
            {
                $et = new Preinscrit();
                $et->cne = $cne_r;
                $et->nomprenom = $nom_r;
                $et->dateNaiss = $date_r;
                $et->academie = $academie_r;
                $et->seriebac = $serie_r;
                $et->province = $province_r;
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