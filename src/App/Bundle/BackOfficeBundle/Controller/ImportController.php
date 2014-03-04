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
use PHPExcel\PHPExcel;



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
    private function importEtudiants($path){
    	//labied younes karoum todo here

        $em = $this->get('doctrine')->getEntityManager();

        //1er temps supression du content de la table etudiant

        /** mettre ca dans la boucle d'insertion **/
        //$etudiant = new Etudiant();
        //$em->persist($etudiant);
        //$em->flush();

        return true;
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
