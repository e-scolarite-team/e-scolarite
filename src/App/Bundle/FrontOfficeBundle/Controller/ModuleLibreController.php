<?php

namespace App\Bundle\FrontOfficeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use App\Bundle\BackOfficeBundle\Entity\Etudiant;
use App\Bundle\BackOfficeBundle\Entity\ResultatElp;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

class ModuleLibreController extends Controller
{
    public function module5Action()
    {
       
        $cne='2927220902'; //sera remplacé par une session 
    	 $rep= $this->getDoctrine()->getEntityManager();
         
       $etudiant=$rep->getRepository('AppBackOfficeBundle:Etudiant')
                     ->findBy(array('cne'=>$cne));
       var_dump($etudiant);
       return new Response('hhh');
    /*
       $resultat_all=$rep->getRepository('AppBackOfficeBundle:ResultatElp')
                     ->findBy(array('id'=>$etudiant->getId()));
             foreach ($resultat_all as  $resultat) 
             {
                    $element=$resultat->getElement ;
                    $tab=  str_split($element);
                    for($i=0;$i<count($tab);$i++)
                    {
                         $numElt=(int)$tab[i];
                         if($numElt!=0)
                         {
                             for($j=0;$j<5;$j++)
                             {
                                $Tabelt[$j]=$numElt ;
                             }
                         }
                    }
                    
                       $liste_elt[]=$Tabelt ; //listes des elment sous la forme 1100 ou 1210 
             }
            
             // semestre va contenir tous les semestres d'un étudaint
             $semestre=array();
             foreach ($liste_elt as $elt) 
             {
                 if( !in_array($elt[0], $semestre))
                 {
                     $semestre[]=$elt[0];
                 }    
             }
             */
       
       //trouver le dernier semestre que l'etudiant à effectué 
       //si ce semestre c'est s3 ou s4 on verifie qu'il à veridé 7 module en s1 et s2
       //meme chose pour les autre semestre
        return $this->render('AppFrontOfficeBundle:ModuleLibre:module5.html.twig',
                array('etudiant' =>$etudiant));
    }
}

