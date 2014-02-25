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

$feuil->setCellValue('A1','N°');
$feuil->setCellValue('B1','cne');
$feuil->setCellValue('C1','cne');
$feuil->setCellValue('D1','nom');
$feuil->setCellValue('E1','prenom');
$feuil->setCellValue('F1','date nai');
$feuil->setCellValue('G1','sexe');
$feuil->setCellValue('H1','cin');
$feuil->setCellValue('I1','nom prén arb');
$feuil->setCellValue('J1','tel');
$feuil->setCellValue('K1','ville nai');
$feuil->setCellValue('M1','date ens sup');
$feuil->setCellValue('N1','code pay');
$feuil->setCellValue('O1','adr1');
$feuil->setCellValue('P1','adr2');
$feuil->setCellValue('Q1','adr3');
$feuil->setCellValue('R1','mail');
$feuil->setCellValue('S1','série bac');
$feuil->setCellValue('T1','académie');
$feuil->setCellValue('U1','departement bac');
$feuil->setCellValue('V1','mention');
$feuil->setCellValue('W1','année bac');

$header = $feuil->getStyle('A1:W1');
$style_header = array(                  
                'fill' => array(
                                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                                'color' => array('rgb'=>'948A54')
                                )
                );
try
{
    $header->applyFromArray($style_header);    
}catch(Exception $e){}

$req =<<<CON
 SELECT `p`.`numero`,`p`.`cne`,`p`.`nom`,`p`.`prenom`,`p`.`datenaissance`,`p`.`sexe`,
        `p`.`cin`,`p`.`nomar` + ' ' + `p`.`prenomar`,`p`.`tel`,`p`.`villenaissance`,
        `p`.`dateins`,SUBSTRING(`p`.`adresse`,1,32) AS `adr1`,
        SUBSTRING(`p`.`adresse`,33,32) AS `adr2`,SUBSTRING(`p`.`adresse`,65,32) AS `adr3`,
        `p`.`email`,`b`.`serie`,`b`.`academie`,`b`.`provetab`,`p`.`mention`,`p`.`anneebac`
FROM `preinscrit` AS `p` INNER JOIN `bacglob` AS `b` ON `p`.`cne`=`b`.`cne`;
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
    $stmt->bind_result($num,$cne,$nom,$prenom,$datenaissance,$sexe,$cin,$nomar,$tel,$villenaissance,$dateins,$adr1,$adr2,$adr3,$email,$serie,$academie,$prov,$mention,$anneebac);

    $i=1;
    while($stmt->fetch())
    {
        $i++;
        try
        {
            $datenaissance = date("d/m/Y",strtotime($datenaissance));
            $feuil->setCellValue("A{$i}",$i - 1);
            $feuil->setCellValue("B{$i}","$cne");
            $feuil->setCellValue("C{$i}","$cne");
            $feuil->setCellValue("D{$i}",$nom);
            $feuil->setCellValue("E{$i}",$prenom);
            $feuil->setCellValue("F{$i}",$datenaissance);
            $feuil->setCellValue("G{$i}",$sexe);
            $feuil->setCellValue("H{$i}",$cin);
            $feuil->setCellValue("I{$i}",$nomar);
            $feuil->setCellValue("J{$i}",$tel);
            $feuil->setCellValue("K{$i}",$villenaissance);
            $feuil->setCellValue("M{$i}","$dateins");
            $feuil->setCellValue("N{$i}",'350');
            $feuil->setCellValue("O{$i}",$adr1);
            $feuil->setCellValue("P{$i}",$adr2);
            $feuil->setCellValue("Q{$i}",$adr3);
            $feuil->setCellValue("R{$i}",$email);
            $feuil->setCellValue("S{$i}",$serie);
            $feuil->setCellValue("T{$i}",$academie);
            $feuil->setCellValue("U{$i}",$prov);
            $feuil->setCellValue("V{$i}",$mention);
            $feuil->setCellValue("W{$i}","$anneebac");
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
    header("Content-Disposition: attachment;filename=opi_bac.xls");
    header("Content-Transfer-Encoding: binary ");
    flush();
    $objWriter->save('php://output');
    exit();
}
else
{
    header("location:gerer-preinscription.php?err=104");
}