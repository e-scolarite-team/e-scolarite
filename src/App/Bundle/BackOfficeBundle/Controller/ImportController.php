<?php

namespace App\Bundle\BackOfficeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use App\Bundle\BackOfficeBundle\Entity\Element;
use App\Bundle\BackOfficeBundle\Entity\Note;
use App\Bundle\BackOfficeBundle\Entity\Module;
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

            case 'filiere':
            $status = $this->importFiliere($path);
            break;

            case 'module':
            $status = $this->importModules($path);
            break;

            case 'note':
            $status = $this->importNotes($path);
            break;

            case 'element':
            $status = $this->importElements($path);
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
    private function importEtudiants($path="uploads/etudiant.xlsx"){
        //labied younes karoum todo here

        $em = $this->get('doctrine')->getEntityManager();
        $this->deleteFrom("Etudiant");
        $status=$this->fillTable("Etudiant",
            array('CodeApogee' =>array("A") ,
                'Cne'=>array("K") ,'Cin'=>array("BI") ,
                'DateNaissance'=>array("Q","d","date") ,'VilleNaissance'=>array("AL") ,
                'Nom'=>array("V") ,'Prenom'=>array("X") ,
                'NomAr'=>array("BG"),'PrenomAr'=>array("BH"),
                'Sexe'=>array("AJ") ,'AnneeInscription'=>array("U") ,
                'AnneeDepart'=>array("U") ,'Adresse'=>array("AL") ),
            $path);
        //1er temps supression du content de la table etudiant

        /** mettre ca dans la boucle d'insertion **/
        //$etudiant = new Etudiant();
        //$em->persist($etudiant);
        //$em->flush();         
        return "1";
    }
/**
 * create's entities of type $entity and saves them to dataBase 
 * @param  String $entity           entity name
 * @param  Array $propToCellsArray array of entity fields ,field=>array(cell,functionToApply)
 * @param  String $path             path to excel file
 * @return Boolean                   if all goes well status equals true
 */
private function fillTable($entity,$propToCellsArray,$path)
{  
    $status=1;
    $workSheet=$this->getWorkSheet($path,0);// create a worksheet
    $highestRow = $workSheet->getHighestRow();// get count of rows in worksheet
    $em=$this->get('Doctrine')->getEntityManager();
    for ($i=2; $i < $highestRow; $i++) { 
        $this->saveRow($workSheet,$entity,$propToCellsArray,$em,$i);
    }
    
    

    return $status;

}
/**
 * save excel row to an entity object
 * @param  phpExcelWorkSheet $workSheet        worksheet in memory
 * @param  Entity<entity> $entity           name of the entity
 * @param  Array $propToCellsArray  array that maps properties to collumns
 * @param  entityManager $em               
 * @param  int $index            row index
 * @return void                  
 */
private function saveRow($workSheet,$entity,$propToCellsArray,$em,$index) 
{
    $entityClass="App\Bundle\BackOfficeBundle\Entity\\".$entity;
    $entityObj=new $entityClass();
    foreach ($propToCellsArray as $key => $paramValues) {
        $setFun='set'.$key;
        $val=count($paramValues)==3?$this->applyFunction($paramValues,$workSheet->getCell($paramValues[0].$index)->getValue()):$workSheet->getCell($paramValues[0].$index)->getValue();
        $entityObj->$setFun($val);
    }
    $em->persist($entityObj);
    $em->flush();
   
}
/**
 * apply a function to a cellValue
 * @param  Array $paramValues array('columnCharacter','predefined function or user defined function (p or d)','function to applay')
 * @param  string $cellValue   value of cell
 * @return type of the function to applay              
 */
private function applyFunction($paramValues,$cellValue)
{
    return ($paramValues[1]=="p")?$paramValues[2]($cellValue):$this->$paramValues[2]($cellValue); 
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
    $objWorksheet = $objPHPExcel->setActiveSheetIndex($sheetIndex);
    return $objWorksheet;
}
    /**
     * delete * from $entity
     * @param  String $entity 
     * @return void         [description]
     */ 
    public function deleteFrom($entity)         
    {
        $em = $this->get('doctrine')->getManager();
        $em->createQuery("delete from App\Bundle\BackOfficeBundle\Entity\\".$entity)->execute();
    }
    /**
     * for route testing
     * @return String [description]
     */
    public function testAction()
    {    
       return new Response($this->importEtudiants("uploads/etudiant.xlsx"));
   }

   /**
    *  converts from excel date to php date
    * @param  cellValue $date cellValue that has excel formated date
    * @return DateTime       php date 
    */
   private function date($date)
   {
    $dateValue = \PHPExcel_Style_NumberFormat::toFormattedString($date, "M/D/YYYY");
       return new \DateTime($dateValue);
    }
    /**
    * @param string
    *
    * @return boolean
    */
    private function importNotes($path){
        //paul todo here

        $em = $this->get('doctrine')->getEntityManager();

        //1er temps supression du content de la table etudiant

        /** mettre ca dans la boucle d'insertion **/
        //$note = new Note();
        //$em->persist($note);
        //$em->flush();

        return true;
    }

    /**
    * @param string
    *
    * @return boolean
    */
    private function importModules($path){
        //elminaoui todo here

        $em = $this->get('doctrine')->getEntityManager();

        //1er temps supression du content de la table etudiant

        /** mettre ca dans la boucle d'insertion **/
        //$module = new Module();
        //$em->persist($module);
        //$em->flush();


        return true;
    }

    /**
    * @param string
    *
    * @return boolean
    */
    private function importElements($path){
        //el mehdi el hajri todo here

        $em = $this->get('doctrine')->getEntityManager();

        //1er temps supression du content de la table etudiant

        /** mettre ca dans la boucle d'insertion **/
        //$element = new Element();
        //$em->persist($element);
        //$em->flush();


        return true;
    }

    /**
    * @param string
    *
    * @return boolean
    */
    private function importFiliere($path){
        //hamid lmajid todo here

        $em = $this->get('doctrine')->getEntityManager();

        //1er temps supression du content de la table etudiant

        /** mettre ca dans la boucle d'insertion **/
        //$element = new Element();
        //$em->persist($element);
        //$em->flush();


        return true;
    }

}
