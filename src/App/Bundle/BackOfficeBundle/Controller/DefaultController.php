<?php

namespace App\Bundle\BackOfficeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;


class DefaultController extends Controller
{
    public function indexAction($name){
    
    	$objPHPExcel = new \PHPExcel();

		$objReader = \PHPExcel_IOFactory::createReader('Excel2007');

		$objReader->setReadDataOnly(true);

		$objPHPExcel = $objReader->load("uploads/exchange/12345.xlsx");

		$objWorksheet = $objPHPExcel->setActiveSheetIndex(0);
		

        return $this->render('AppBackOfficeBundle:Default:index.html.twig', array('name' => $name));
    }
}
