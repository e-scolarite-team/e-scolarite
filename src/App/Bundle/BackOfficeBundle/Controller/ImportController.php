<?php

namespace App\Bundle\BackOfficeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

use App\Bundle\BackOfficeBundle\Entity\Element;
use App\Bundle\BackOfficeBundle\Entity\Note;
use App\Bundle\BackOfficeBundle\Entity\Module;
use App\Bundle\BackOfficeBundle\Entity\Etudiant;

use App\Bundle\BackOfficeBundle\Form\ImportFormType;

use PHPExcel\PHPExcel;



class ImportController extends Controller
{
    public function updateAction()
    {
    	//abdelatif karoum todo here
        $form  = $this->createForm(new ImportFormType());
        $status = $this->importElements('uploads/votre.xls');

        if($status)
            return new Response('table import!!');

        return new Response('table error!!');

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

}
