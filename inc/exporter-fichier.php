<?php
ignore_user_abort(true);
set_time_limit(0);

include_once('classes/PHPExcel.php');
include_once('classes/PHPExcel/Reader/Excel2007.php');

function envoi()
{
    try
        {
            //verifier les parametres.
            if( !isset($_FILES['doc']))
            {
                header("Location:envoyer-fichier.php?err=104");
                exit();
            }
            
            $err=null;
            if ($_FILES['doc']['error'] !=0)
            {
                $err = 105;
            }
            
            if( $err != null )
            {
                header("location:envoyer-fichier.php?err=" . $err);
                exit();
            }
            
            // verifier l'extension
            $ext = strtolower( pathinfo($_FILES['doc']['name'], PATHINFO_EXTENSION));
            
            if($ext!='xlsx' and $ext!='csv')
            {
                header("location:envoyer-fichier.php?err=106" );
                exit();
            }
            $name;
            // selection destination
            switch($_POST["fichier"])
            {
                case 1:
                    $name = 'apogee_resultat';
                    break;
                case 2:
                    $name = 'apogee_contrat';
                    break;
                case 3:
                    $name = 'apogee_element';
                    break;
                case 4:
                    $name = 'apogee_ind';
                    break;
                default:
                    $name = null;
                    break;
            }
            
            if(!is_null($name))
            {
                $fn = $name . (string)time() . '.' . $ext;
                $chemin = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'doc' . DIRECTORY_SEPARATOR . $fn;
                move_uploaded_file($_FILES['doc']['tmp_name'], $chemin);
                
                if($ext=='xlsx')
                {
                    importer_xlsx($fn);
                }
                else
                {
                    importer_csv($chemin);
                }
                
                @unlink($_SERVER['DOCUMENT_ROOT'] . 'faculte/scolarite/doc/' . $fn);
                header("location:envoyer-fichier.php?i=100");
                exit();
            }
            else
            {
                header("location:envoyer-fichier.php?err=107" );
            }
            
        }
        catch (Exception $e)
        {
                header("location:envoyer-fichier.php?err=108");
                exit();
        }
}

function importer_xlsx($fn)
{
    switch($_POST["fichier"])
    {
        case 1:
            importer_resultat($fn);
            break;
        case 2:
            importer_contrat($fn);
            break;
        case 3:
            importer_element($fn);
            break;
        case 4:
            importer_ind($fn);
            break;
        default:
            break;
    }
}

function importer_csv($chemin)
{
    $doc = lire_csv($chemin);
    array_shift($doc);
    
    switch($_POST["fichier"])
    {
        case 1:
            csv_resultat($doc);
            break;
        case 2:
            csv_contrat($doc);
            break;
        case 3:
            csv_element($doc);
            break;
        case 4:
            csv_ind($doc);
            break;
        default:
            break;
    }
}

function importer_ind($fn)
{
    $objReader = PHPExcel_IOFactory::createReader('Excel2007');
    $objReader->setReadDataOnly(true);
    $objPHPExcel = $objReader->load("inc/doc/" . $fn);
    $objWorksheet = $objPHPExcel->setActiveSheetIndex(0);
    db_engine::connecter(SERVER,DB_USER,DB_PASS,DB);
    $query = "INSERT INTO `etudiant`(`cne`,`codeind`,`nom`,`prenom`,`datenaissance`,`cin`,`sexe`,`nomar`,`prenomar`,`villenaissance`)VALUES(?,?,?,?,?,?,?,?,?,?);";
    $i=1;
    $stmt;
    $vals = array();
    if($stmt = db_engine::preparer($query))
    {
        do {
            $i++;
            $codeind = "";
            
            try
            {
                $codeind =  $objWorksheet->getCell("A{$i}")->getValue();
                $cne =  $objWorksheet->getCell("B{$i}")->getValue();
                $nom = $objWorksheet->getCell("C{$i}")->getValue();
                $prenom = $objWorksheet->getCell("D{$i}")->getValue();
                $nomar = $objWorksheet->getCell("E{$i}")->getValue();
                $prenomar = $objWorksheet->getCell("F{$i}")->getValue();
                $cin = $objWorksheet->getCell("G{$i}")->getValue();
                $datenaiss = $objWorksheet->getCell("H{$i}")->getValue();
                $sexe = $objWorksheet->getCell("I{$i}")->getValue();
                $ville = $objWorksheet->getCell("J{$i}")->getValue();
                //$villear = $objWorksheet->getCell("K{$i}")->getValue();
                
                $datenaiss = convertir_date($datenaiss);
               if(trim($codeind)=="")
                {
                    continue;
                }
                
                $stmt->bind_param("ssssssssss",$cne,$codeind,$nom,$prenom,$datenaiss,$cin,$sexe,$nomar,$prenomar,$ville);

                $stmt->execute();
            }catch(Exception $e)
            {
            }
            
        }while($codeind!="");
        $stmt->close();
        db_engine::fermer();
        unset($stmt);
    }
    else
    {
        header("location:envoyer-fichier.php?err=109");
        exit();
    } 
}

function importer_element($fn)
{
    $objReader = PHPExcel_IOFactory::createReader('Excel2007');
    $objReader->setReadDataOnly(true);
    $objPHPExcel = $objReader->load("inc/doc/" . $fn);
    $objWorksheet = $objPHPExcel->setActiveSheetIndex(0);
    db_engine::connecter(SERVER,DB_USER,DB_PASS,DB);
    $query = "INSERT INTO `elementpedagogique`(`code`,`type`,`libele1`,`libele2`,`parent`)VALUES(?,?,?,?,?);";
    $i=1;
    $stmt;
    $vals = array();
    if($stmt = db_engine::preparer($query))
    {
        do {
            $i++;
            
            try
            {
                $codeelem =  $objWorksheet->getCell("A{$i}")->getValue();
                $type =  $objWorksheet->getCell("B{$i}")->getValue();
                $lib1 = $objWorksheet->getCell("C{$i}")->getValue();
                $lib2 = $objWorksheet->getCell("D{$i}")->getValue();
                $parent = $objWorksheet->getCell("E{$i}")->getValue();
                
               if(trim($codeelem)=="")
                {
                    continue;
                }
                
                $stmt->bind_param("sssss",$codeelem,$type,$lib1,$lib2,$parent);
                $stmt->execute();
                
            }catch(Exception $e)
            {
            }
            
        }while(trim($codeelem)!="");
        $stmt->close();
        db_engine::fermer();
        unset($stmt);
    }
    else
    {
        header("location:envoyer-fichier.php?err=109");
        exit();
    }  
}

function importer_resultat($fn)
{
    $objReader = PHPExcel_IOFactory::createReader('Excel2007');
    $objReader->setReadDataOnly(true);
    $objPHPExcel = $objReader->load("inc/doc/" . $fn);
    $objWorksheet = $objPHPExcel->setActiveSheetIndex(0);
    db_engine::connecter(SERVER,DB_USER,DB_PASS,DB);
    $query = "INSERT INTO `notes`(`ind`,`element`,`note`,`session`,`etat`,`annee`)VALUES(?,?,?,?,?,?);";
    $i=1;
    $stmt;
    $vals = array();
    if($stmt = db_engine::preparer($query))
    {
        do {
            $i++;
            $ind = '';
            try
            {
                $ind =  $objWorksheet->getCell("A{$i}")->getValue();
                $elem =  $objWorksheet->getCell("B{$i}")->getValue();
                $annee = $objWorksheet->getCell("C{$i}")->getValue();
                $session = $objWorksheet->getCell("D{$i}")->getValue();
                $note = $objWorksheet->getCell("E{$i}")->getValue();
                $etat = $objWorksheet->getCell("F{$i}")->getValue();
                
               if(trim($ind)=="")
                {
                    continue;
                }
                
                $stmt->bind_param("ssdisi",$ind,$elem,$note,$session,$etat,$annee);
                $stmt->execute();
               
            }catch(Exception $e)
            {
            }
            
        }while(trim($ind)!="");
        $stmt->close();
        db_engine::fermer();
        unset($stmt);
    }
    else
    {
        header("location:envoyer-fichier.php?err=109");
        exit();
    }     
}

function importer_contrat($fn)
{
    $objReader = PHPExcel_IOFactory::createReader('Excel2007');
    $objReader->setReadDataOnly(true);
    $objPHPExcel = $objReader->load("inc/doc/" . $fn);
    $objWorksheet = $objPHPExcel->setActiveSheetIndex(0);
    db_engine::connecter(SERVER,DB_USER,DB_PASS,DB);
    $query = "INSERT INTO `contrat`(`ind`,`etape`,`element`,`annee`)VALUES(?,?,?,?);";
    $i=1;
    $stmt;
    $vals = array();
    if($stmt = db_engine::preparer($query))
    {
        do {
            $i++;
            
            try
            {
                $annee =  $objWorksheet->getCell("A{$i}")->getValue();
                $ind =  $objWorksheet->getCell("B{$i}")->getValue();
                $etp = $objWorksheet->getCell("C{$i}")->getValue();
                $elem = $objWorksheet->getCell("D{$i}")->getValue();
                
               if(trim($ind)=="")
                {
                    continue;
                }
                
                
                $stmt->bind_param("sssi",$ind,$etp,$elem,$annee);
                $stmt->execute();
                
            }catch(Exception $e)
            {
            }
            
        }while(trim($ind)!="");
        $stmt->close();
        db_engine::fermer();
        unset($stmt);
    }
    else
    {
        header("location:envoyer-fichier.php?err=109");
        exit();
    }     
}

function csv_resultat($doc)
{
    db_engine::connecter(SERVER,DB_USER,DB_PASS,DB);
    $query = "INSERT INTO `notes`(`ind`,`element`,`note`,`session`,`etat`,`annee`)VALUES(?,?,?,?,?,?);";
    $stmt;
    if($stmt = db_engine::preparer($query))
    {
        foreach($doc as $resultat)
        {
            try
            {
                $stmt->bind_param("sssi",$resultat[0],$resultat[1],$resultat[4],$resultat[3],$resultat[5],$resultat[2]);
                $stmt->execute();
            }catch(Exception $e)
            {
            }
        }
        $stmt->close();
        db_engine::fermer();
        unset($stmt);
    }
    else
    {
        header("location:envoyer-fichier.php?err=109");
        exit();
    }
}

function csv_contrat($doc)
{
    db_engine::connecter(SERVER,DB_USER,DB_PASS,DB);
    $query = "INSERT INTO `contrat`(`ind`,`etape`,`element`,`annee`)VALUES(?,?,?,?);";
    $stmt;
    if($stmt = db_engine::preparer($query))
    {
        foreach($doc as $contrat)
        {
            try
            {
                $stmt->bind_param("sssi",$contrat[1],$contrat[2],$contrat[3],$contrat[0]);
                $stmt->execute();
                
            }catch(Exception $e)
            {
            }
        }
        $stmt->close();
        db_engine::fermer();
        unset($stmt);
    }
    else
    {
        header("location:envoyer-fichier.php?err=109");
        exit();
    }
}

function csv_element($doc)
{
    db_engine::connecter(SERVER,DB_USER,DB_PASS,DB);
    $query = "INSERT INTO `elementpedagogique`(`code`,`type`,`libele1`,`libele2`,`parent`)VALUES(?,?,?,?,?);";
    $stmt;
    if($stmt = db_engine::preparer($query))
    {
        foreach($doc as $element)
        {
            try
            {
                if($stmt = db_engine::preparer($query))
                {
                    $stmt->bind_param("sssss",$element[0],$element[1],$element[2],$element[3],$element[4]);
                    $stmt->execute();
                }
            }catch(Exception $e)
            {
            }
            
        }
        $stmt->close();
        db_engine::fermer();
        unset($stmt);
    }
    else
    {
        header("location:envoyer-fichier.php?err=109");
        exit();
    }
}

function csv_ind($doc)
{
    db_engine::connecter(SERVER,DB_USER,DB_PASS,DB);
    $query = "INSERT INTO `etudiant`(`cne`,`codeind`,`nom`,`prenom`,`datenaissance`,`cin`,`sexe`,`nomar`,`prenomar`,`villenaissance`)VALUES(?,?,?,?,?,?,?,?,?,?);";
    $stmt;
    if($stmt = db_engine::preparer($query))
    {
        foreach($doc as $etudiant)
        {
            try
            {
                $etudiant[7] = convertir_date($etudiant[7]);
                $stmt->bind_param("ssssssssss",$etudiant[1],$etudiant[0],$etudiant[2],$etudiant[3],$etudiant[7],$etudiant[6],$etudiant[8],$etudiant[4],$etudiant[5],$etudiant[9]);
                $stmt->execute();
            }catch(Exception $e)
            {
            }
        }
        $stmt->close();
        db_engine::fermer();
        unset($stmt);
    }
    else
    {
        header("location:envoyer-fichier.php?err=109");
        exit();
    }
    
}


function lire_csv($chemin)
{
	$lignes = array();
	foreach( file($chemin,FILE_IGNORE_NEW_LINES) as $ligne)
	{
        $element = str_getcsv($ligne,";");
        if($element[2]!=='')
	       $lignes[] = $element;
	}
	return $lignes;
}

function convertir_date($d)
{
    $resultat=null;
	$da = explode("/",$d);
    if(count($da)==3)
    {
       $resultat = $da[2] . "-" . $da[1] . "-" . $da[0]; 
    }
    else
    {
        $resultat = date("Y-m-d",mktime(0,0,0,1,$d-1,1900));
    }
	return $resultat;
}


















