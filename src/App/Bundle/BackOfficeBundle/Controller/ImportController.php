<?php

namespace App\Bundle\BackOfficeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use App\Bundle\BackOfficeBundle\Entity\ElementPedagogi;
use App\Bundle\BackOfficeBundle\Entity\Etudiant;
use App\Bundle\BackOfficeBundle\Form\Data\ImportData;

use App\Bundle\BackOfficeBundle\Form\ImportFormType;

use Symfony\Component\HttpFoundation\File\UploadedFile;
//use PHPExcel\PHPExcel;



class ImportController extends Controller
{
    private $folder = "uploads/exchange/";

    public function updateAction(Request $request)
    {
        //abdellatif karroum todo here
        $errors = array();
        
        $form  = $this->createForm(new ImportFormType(),new ImportData());

        if ($request->isMethod('POST')){

            $form->handleRequest($request);

            $validator = $this->get("validator");
            
            $translator  = $this->get('translator');

            $errList = $validator->validate($form);        

            if(count($errList) > 0){

                foreach ($errList as $err) {
                   // trans($id, array $parameters = array(), $domain = null, $locale = null)
                 $errors[] =  $translator->trans($err->getMessage(),array('table' => "abdo fayssal"), 'messages', 'fr_FR');

             }

         }else
         $errors = $this->prepareData($form->getData());                         


     }

     return $this->render('AppBackOfficeBundle:Import:update.html.twig', array('form' => $form->createView(), 'errors' => $errors));
 }


    /**
    * @param mixed
    * @return string error msg
    */
    private function prepareData($data){

        $status = false;

        $attachement = $data->getAttachement();
        $table = $data->getTable();

        $type = $attachement->guessExtension();
        $filename = $table.uniqid().".".$type;

        $attachement->move($this->folder,$filename);

        $path = $this->folder.$filename;

        switch ($table) {
            case 'etudiant':
            $status = $this->importEtudiants($path);
            break;            

            case 'note':
            $status = $this->importNotes($path);
            break;

            case 'element':
            $status = $this->importElementsPedagogi($path);
            break;                                               
            
        }
        if(!$status)
            return "errors.import.parse";

        return "data.fill.success";
    }

    /**
    * @param string
    *
    * @return boolean
    */
    private function importEtudiants($path){
        //labied younes karoum todo here

        $em = $this->get('doctrine')->getManager();       
        $em->getRepository("AppBackOfficeBundle:Etudiant")->deleteAll();
        $etudiantExel = $this->getWorkSheet($path,0);
        $metaData = $this->getMetaData($etudiantExel);
        
        $highestRow = $etudiantExel->getHighestRow();       
        
        for ($i=2; $i <= $highestRow; $i++) { 

            $entity = new Etudiant($this->container);
            
            $entity->setId($etudiantExel->getCellByColumnAndRow($metaData["COD_IND"], $i)->getValue());
            $entity->setCodeAppogee($etudiantExel->getCellByColumnAndRow($metaData["COD_ETU"], $i)->getValue());
            $entity->setCne($etudiantExel->getCellByColumnAndRow($metaData["COD_NNE_IND"], $i)->getValue());
            $entity->setCin($etudiantExel->getCellByColumnAndRow($metaData["CIN_IND"], $i)->getValue());
            $entity->setDateNaissance($this->date($etudiantExel->getCellByColumnAndRow($metaData["DATE_NAI_IND"], $i)->getValue()));//$dateInsc);
            $entity->setVilleNaissance($etudiantExel->getCellByColumnAndRow($metaData["LIB_VIL_NAI_ETU"], $i)->getValue());
            $entity->setNom($etudiantExel->getCellByColumnAndRow($metaData["LIB_NOM_PAT_IND"], $i)->getValue());
            $entity->setPrenom($etudiantExel->getCellByColumnAndRow($metaData["LIB_PR1_IND"], $i)->getValue());
            $entity->setNomAr($etudiantExel->getCellByColumnAndRow($metaData["LIB_NOM_IND_ARB"], $i)->getValue());
            $entity->setPrenomAr($etudiantExel->getCellByColumnAndRow($metaData["LIB_PRN_IND_ARB"], $i)->getValue());
            $entity->setSexe($etudiantExel->getCellByColumnAndRow($metaData["COD_SEX_ETU"], $i)->getValue());
            $entity->setAnneeInscription($etudiantExel->getCellByColumnAndRow($metaData["DAA_ENT_ETB"], $i)->getValue());
            $entity->setAdresse($etudiantExel->getCellByColumnAndRow($metaData["LIB_VIL_NAI_ETU"], $i)->getValue());           
            //$entity->preparePassword();
            $em->persist($entity);  
            $em->flush();           
        }
      
        return true;
    }

    /**
    * @param string
    *
    * @return boolean
    */
    private function importNotes($path){
        //paul todo here

        $em = $this->get('doctrine')->getManager();        
        $em->getRepository("AppBackOfficeBundle:ResultatElp")->deleteAll();
        $sheet = $this->getWorkSheet($path,0);
        $metaData = $this->getMetaData($etudiantExel);
        
        $highestRow = $etudiantExel->getHighestRow();       
        
        for ($i=2; $i <= $highestRow; $i++) { 
            $entity = new Etudiant($this->container);
            $etudiant = $em->getRepository("AppBackOfficeBundle:Etudiant")->find($sheet->getCellByColumnAndRow($metaData["COD_IND"], $i)->getValue());
            $element = $em->getRepository("AppBackOfficeBundle:ElementPedagogi")->find($sheet->getCellByColumnAndRow($metaData["COD_ELP"], $i)->getValue());
            $entity->setEtudiant($etudiant);            
            $entity->setElement($element);            
            $entity->setAnnee($sheet->getCellByColumnAndRow($metaData["COD_ANU"], $i)->getValue());
            $entity->setSession($sheet->getCellByColumnAndRow($metaData["COD_SES"], $i)->getValue());
            $entity->setAdmissibilite($sheet->getCellByColumnAndRow($metaData["COD_ADM"], $i)->getValue());
            $entity->setNote($sheet->getCellByColumnAndRow($metaData["NOT_ELP"], $i)->getValue());
            $entity->setStatus($this->date($sheet->getCellByColumnAndRow($metaData["COD_TRE"], $i)->getValue()));//$dateInsc);            
            
            $em->persist($entity);  
            $em->flush();           
        }
        
                
        return true;
    }

    
    /**
    * @param string
    *
    * @return boolean
    */
    private function importElementsPedagogi($path){
        $em = $this->get('doctrine')->getManager();        
        $em->getRepository("AppBackOfficeBundle:ElementPedagogi")->deleteAll();
        $sheet = $this->getWorkSheet($path,0);
        $metaData = $this->getMetaData($sheet);
        
        $highestRow = $sheet->getHighestRow();       
        
        for ($i=2; $i <= $highestRow; $i++) { 
            $entity = new ElementPedagogi();
            
            $entity->setCode($sheet->getCellByColumnAndRow($metaData["COD_ELP"], $i)->getValue());
            $entity->setNature($sheet->getCellByColumnAndRow($metaData["COD_NEL"], $i)->getValue());
            $entity->setLib($sheet->getCellByColumnAndRow($metaData["LIB_ELP"], $i)->getValue());
            $entity->setLic($sheet->getCellByColumnAndRow($metaData["LIC_ELP"], $i)->getValue());
            $entity->setEtat($sheet->getCellByColumnAndRow($metaData["ETA_ELP"], $i)->getValue());//$dateInsc);
            $entity->setLibAr($sheet->getCellByColumnAndRow($metaData["LIB_ELP_ARB"], $i)->getValue());                        
            $entity->setLicAr($sheet->getCellByColumnAndRow($metaData["LIC_ELP_ARB"], $i)->getValue());                        
            $entity->setCreatedAt($this->date($sheet->getCellByColumnAndRow($metaData["DAT_CRE_ELP"], $i)->getValue()));
            $updateDat = $sheet->getCellByColumnAndRow($metaData["DAT_MOD_ELP"], $i)->getValue();
            if($updateDat != null)$entity->setUpdatedAt($this->date($sheet->getCellByColumnAndRow($metaData["DAT_MOD_ELP"], $i)->getValue()));
            $em->persist($entity);  
            $em->flush();           
        }
                
        return true;
    }

    /**
    * @param 
    *
    * @return 
    */

    private function getMetaData($data){
        //labied younes karoum todo here

        $nbColumn = $data->getHighestColumn();
        $nbColumnIndex = \PHPExcel_Cell::columnIndexFromString($nbColumn);
        $metaData = array();
        for($i = 0; $i < $nbColumnIndex; $i++){
            $col = $data->getCellByColumnAndRow($i, 1)->getValue();
            $metaData[$col] = $i;
        }    
         
        return $metaData;   
       
    }

    /**
     * creates a phpExcelObject and sets its active sheet to the passed $sheetIndex
     * @param  String $path       $path to excel file
     * @param  int $sheetIndex 
     * @return phpExcelObject             
     */
    private function getWorkSheet($path,$sheetIndex)
    {
        ini_set("max_execution_time", "200");
        $cacheMethod=\PHPExcel_CachedObjectStorageFactory::cache_to_sqlite;
        \PHPExcel_Settings::setCacheStorageMethod($cacheMethod); 
        $objPHPExcel = new \PHPExcel();
        $objReader = \PHPExcel_IOFactory::createReader(\PHPExcel_IOFactory::identify($path));
        $objPHPExcel = $objReader->load($path);
        $objWorksheet = $objPHPExcel->setActiveSheetIndex(0);
        return $objWorksheet;
    }  

    /**
    *  converts from excel date to php date
    * @param  cellValue $date cellValue that has excel formated date
    * @return DateTime       php date 
    */
    private function date($date){
       
        $dateValue = \PHPExcel_Style_NumberFormat::toFormattedString($date, "dd-m-yyyy");
        return new \DateTime($dateValue);
    }

   

}
