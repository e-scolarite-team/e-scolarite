<?php

require_once("inc/pagebase.php");

if(!is_loggedAdmin())
{
    redirectToPage("gestion.php");
    exit();
}

ignore_user_abort(true);
set_time_limit(0);
require_once("inc/db_engine.php");
include_once('classes/PHPExcel.php');
include_once('classes/PHPExcel/Writer/Excel2007.php');



$objPHPExcel = new PHPExcel();

$objPHPExcel->setActiveSheetIndex(0);
$feuil = $objPHPExcel->getActiveSheet();

$feuil->setCellValue('A2','N°');
$feuil->setCellValue('B2','cne');
$feuil->setCellValue('C2','NOM PRENOM');
$feuil->setCellValue('D2','MODULE DEMANDE');

$req =<<<CON
    SELECT `d`.`cne`, CONCAT(`e`.`prenom`,' ',`e`.`nom`), `d`.`modules` FROM `demandelibre` AS `d` INNER JOIN `etudiant` AS `e` ON `d`.`cne`=`e`.`cne`;
CON;
db_engine::connecter(SERVER,DB_USER,DB_PASS,DB);
if($stmt = db_engine::preparer($req))
{
    while (ob_get_level() > 0) 
    {
        ob_end_clean();
    }

    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($cne,$nom,$mod);

    $i=2;
    while($stmt->fetch())
    {
        $i++;
        try
        {
            $feuil->setCellValue("A{$i}",$i - 2);
            $feuil->setCellValue("B{$i}","$cne");
            $feuil->setCellValue("C{$i}","$nom");
            $feuil->setCellValue("D{$i}","$mod");
        }
        catch(Exception $e){}
    }
    $stmt->close();
    db_engine::fermer();

    /* Sauvegarder */
    $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
    $objWriter->setOffice2003Compatibility(true);
    header("Pragma: public");
    header("Expires: 0");
    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
    header("Content-Type: application/force-download");
    header("Content-Type: application/octet-stream");
    header("Content-Type: application/download");;
    header("Content-Disposition: attachment;filename=Module_Libre.xls");
    header("Content-Transfer-Encoding: binary ");
    flush();
    $objWriter->save('php://output');
    exit();
}
else
{
    header("location:gerer-preinscription.php?err=104");
}