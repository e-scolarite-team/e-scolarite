<?php

namespace App\Bundle\BackOfficeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use App\Bundle\BackOfficeBundle\Entity\Element;
use App\Bundle\BackOfficeBundle\Entity\Note;
use App\Bundle\BackOfficeBundle\Entity\Module;
use App\Bundle\BackOfficeBundle\Entity\Etudiant;

use App\Bundle\BackOfficeBundle\Form\ImportFormType;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use PHPExcel\PHPExcel;



class ImportController extends Controller
{
    public function updateAction(Request $request)
    {
    	//abdelatif karoum todo here
        $message = "Les donnees sont inseres ";
        $form  = $this->createForm(new ImportFormType());
        $status = false;
        $form->handleRequest($request);
        if ($request->isMethod('POST'))

            if($form->isValid()){  
                $folder = "upload/excel/";        
                $data = $form->getData();              
                $attachement = $data['attachement'];
                $table = $data['table'];
                $type = $attachement->guessExtension();
                $nomFichier = $table.uniqid().".".$type;
                $attachement->move($folder,$nomFichier);
                $path = $folder.$nomFichier;
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
                if(!$status)$message = "les donnees sont errones ";             
            }
        }
        
        return array('form' => $form->createView(), 'message' => $message);



        //return $this->render('AppBackOfficeBundle:Import:update.html.twig', array('form' => $form->createView()));
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
