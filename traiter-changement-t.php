<?php
require_once("inc/db_engine.php");
require_once("inc/adminbase.php");

class Envoyer extends BaseAdmin
{
    public function __construct()
    {
        parent::__construct();
    }
        
    public function traiter_donnees()
    {
        parent::traiter_donnees();
        if(count($_POST)>0)
        {            
            db_engine::connecter(SERVER,DB_USER,DB_PASS,DB);
            $nb = 0;
            try
            {
                foreach($_POST as $num => $rec)
                {
                    $req = "UPDATE `reclamationchanger` SET `remarque` = ?,`etat` = ? WHERE `numero`=?;";
                    if($stmt = db_engine::preparer($req))
                    {
                        if(isset($rec['decision']))
                        {
                            $rec['decision'] = ($rec['decision'] == 1) ? 1 : 0;
                            $stmt->bind_param("sii",$rec['remarque'],$rec['decision'],$num);
                            $stmt->execute();
                            $i++;
                        
                            $req = "DELETE FROM `reclamationchangerprog` WHERE `num`=?;";
                            if($stmt = db_engine::preparer($req))
                            {
                                $stmt->bind_param("i",$num);
                                $stmt->execute();
                            }
                            if(isset($rec['a']))
                            {
                                $req = "INSERT INTO `reclamationchangerprog`(`num`,`moduleaffiche`)VALUES(?,?);";
                                if($stmt = db_engine::preparer($req))
                                {
                                    foreach($rec['a'] as $a)
                                    {
                                        $stmt->bind_param("is",$num,$a);
                                        $stmt->execute();
                                    }
                                }
                            }
                            $req = "DELETE FROM `reclamationchangerrec` WHERE `num`=?;";
                            if($stmt = db_engine::preparer($req))
                            {
                                $stmt->bind_param("i",$num);
                                $stmt->execute();
                            }
                            if(isset($rec['r']))
                            {
                                $req = "INSERT INTO `reclamationchangerrec`(`num`,`modulereclame`)VALUES(?,?);";
                                if($stmt = db_engine::preparer($req))
                                {
                                    foreach($rec['r'] as $r)
                                    {
                                        $stmt->bind_param("is",$num,$r);
                                        $stmt->execute();
                                    }
                                }
                            }
                        }
                    }
                }
            }
            catch(Exception $e)
            {}
            
            redirectToPage("traiter-changement.php?i=100&nb=$i");
            exit();
        }
        redirectToPage("traiter-module.php");
        exit();
    }
}

$page = new Envoyer();
$page->traiter_requete();