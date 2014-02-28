<?php

namespace App\Bundle\BackOfficeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ImportController extends Controller
{
    public function updateAction()
    {
    	//abdelatif karoum todo here
        return $this->render('AppBackOfficeBundle:Import:update.html.twig', array());
    }

    private function importEtudiants(){
    	//labied younes karoum todo here
    }

    private function importNotes(){
    	//paul todo here
    }

    private function importModules(){
    	//elminaoui todo here
    }

    private function importElements(){
    	//el mehdi el hajri todo here
    }

}
