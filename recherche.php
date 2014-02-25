<?php
require_once("inc/db_engine.php");
require_once("inc/pagebase.php");

if(!is_loggedAdmin())
{
    redirectToPage("gestion.php");
    exit();
}

//simulation de requete distante
    //sleep(2);


$cne_old = (empty($_POST['cne_old']))? '' : trim($_POST['cne_old']);

if(!empty($cne_old))
{
    $req = "UPDATE `etudiant` SET `nom`=?,`prenom`=?,`cne`=?,`datenaissance`=?,`nomar`=?,`prenomar`=? WHERE `cne` = ? ;";
    db_engine::connecter(SERVER,DB_USER,DB_PASS,DB);
    if($stmt = db_engine::preparer($req))
    {
        $stmt->bind_param('sssssss',$_POST['nom'],$_POST['prenom'],$_POST['cne'],$_POST['datenaiss'],$_POST['nomar'],$_POST['prenomar'],$cne_old);
        $status = $stmt->execute();
        
        if($status)
        {
            echo '100';
        }
        else
        {
            echo '104';
        }
    }
    exit();
}

$nom = (empty($_POST['nom']))? '' : trim($_POST['nom']);
$etudiant = array();

if(!empty($nom))
{
    $req = "SELECT `etudiant`.`nom`,`etudiant`.`prenom`,`etudiant`.`cne` FROM `etudiant` WHERE `nom` LIKE (CONCAT(?,'%')) ORDER BY `etudiant`.`nom` ASC;";
    db_engine::connecter(SERVER,DB_USER,DB_PASS,DB);
    if($stmt = db_engine::preparer($req))
    {
        $stmt->bind_param('s',$nom);
        $stmt->execute();
        $stmt->store_result();
        if($stmt->num_rows <1)
        {
            exit();
        }
        $stmt->bind_result($n,$prenom,$cne);
        header("Content-Type: application/json");
        
        echo '[';
        while($stmt->fetch())
        {
            $n= hspc($n);
            $prenom = hspc($prenom);
            $cne = hspc($cne);
            $etudiant[] = '{"nom":"' . $n . '","prenom":"' . $prenom . '","cne":"' . $cne . '"}' ; 
        }
        $et = implode(',',$etudiant);
        echo $et;
        echo "]";
    }
    exit();
}

$cne = (empty($_POST['cne']))? '' : trim($_POST['cne']);

if(!empty($cne))
{
    $req = "SELECT `etudiant`.`nom`,`etudiant`.`prenom`,`etudiant`.`cne`,`etudiant`.`datenaissance`,`etudiant`.`nomar`,`etudiant`.`prenomar` FROM `etudiant` WHERE `cne` = ? ;";
    db_engine::connecter(SERVER,DB_USER,DB_PASS,DB);
    if($stmt = db_engine::preparer($req))
    {
        $stmt->bind_param('s',$cne);
        $stmt->execute();
        $stmt->store_result();
        if($stmt->num_rows <1)
        {
            exit();
        }
        $stmt->bind_result($n,$prenom,$c,$dateN,$nomar,$prenomar);
        header("Content-Type: application/json");
        
        echo '[';
        while($stmt->fetch())
        {
            $n= hspc($n);
            $prenom = hspc($prenom);
            $cne = hspc($cne);
            $etudiant[] = '{"nom":"' . $n . '","prenom":"' . $prenom . '","datenaiss":"' . $dateN . '","cne":"' . $c . '","nomar":"' .$nomar . '","prenomar":"'.$prenomar.'"}' ; 
        }
        $et = implode(',',$etudiant);
        echo $et;
        echo "]";
    }
    exit();
}
