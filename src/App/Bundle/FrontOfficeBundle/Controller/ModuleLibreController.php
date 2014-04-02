<?php

namespace App\Bundle\FrontOfficeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use App\Bundle\BackOfficeBundle\Entity\Etudiant;
use App\Bundle\BackOfficeBundle\Entity\ResultatElp;
use App\Bundle\BackOfficeBundle\Entity;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

class ModuleLibreController extends Controller
{
    public function module5Action()
    {
       
      $idUser=$this->getUser()->getId() ;
    	 $rep= $this->getDoctrine()->getEntityManager();
         
      $resultat=$rep->getRepository('AppBackOfficeBundle:ResultatElp')
                     ->tousResultatEtudiant($idUser);
      
//::::::::::::  Ce code permet de determiner le dernier semestre d'un étudiant ::::::::::::::::::::::::::::::::::::
       
             //foreach ($resultat as  $result){
                 $element = $resultat[0]->getElement()->getCode() ;
                 $status= $resultat[0]->getStatus() ;
                 $annee=$resultat[0]->getAnnee();
             echo $element .'<br/>';
             echo $status .'<br/>';
             echo $annee;
             echo count($resultat);
             
             
              /*
                    $tab=  str_split($element);//transforme $element en tableau 
                    $i=0;
                    $semestre=0; //variable permettant de stocker mon semestre
                    while (($i< count($tab)) && ($semestre == 0)){
                        $semestre = (int)$tab[$i];
                    
                        if(($semestre!=0)){ //cette clause à des limites pour GEO2S600 qui correspond au semestre 6

                           $tabsemestre[$semestre]=$element ;  
                        }
                          $i++;
                    }
           
                  }
                    echo '<pre>';
                    var_dump($tabsemestre);
                    echo '<pre>';
                    echo '<br/>';
                    echo  max($tabsemestre) ;
                    echo '<br/>';*/
             
                return new Response('fin');    
             }
 //:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::                   
     
}



