<?php

namespace App\Bundle\FrontOfficeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use App\Bundle\BackOfficeBundle\Entity\Etudiant;
use App\Bundle\BackOfficeBundle\Entity\ResultatElp;
use App\Bundle\BackOfficeBundle\Entity\Demande;
use App\Bundle\BackOfficeBundle\Entity;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

class ModuleLibreController extends Controller
{
    public function module5Action(){
      $session =$this->container->get("esconfig_manager")->getCurrentSemester();
   
      $idUser=$this->getUser()->getId() ;
    	 $rep= $this->getDoctrine()->getManager();  
         
     $LastSemestreObjet=$rep->getRepository('AppBackOfficeBundle:ResultatElp') //objet representant le dernier semestre
                     ->tousResultatEtudiant($idUser);
             echo $LastSemestreObjet->getElement()->getCode().'<br/>';
    
//::::::::::::  Ce code permet de determiner le dernier semestre d'un étudiant ::::::::::::::::::::::::::::::::::::
       
               $codeSemetre = $LastSemestreObjet->getElement()->getCode() ;
                 $status= $LastSemestreObjet->getStatus() ;
                 
                  $tab=  str_split($codeSemetre);//transforme $element en tableau 
                    $i=0;
                    $semestre=0; //variable permettant de stocker mon semestre
                    while (($i < count($tab)) && ($semestre == 0)){//cette clause à des limites pour GEO2S600 qui correspond au semestre 6
                        $semestre = (int)$tab[$i];
                          $i++;
                    }
                      
             if( $status=='V'){//le status du semestre
             return $this->render('AppFrontOfficeBundle:ModuleLibre:PasdeDroit5e.html.twig');
             }
             elseif ($session==1){//la session 1
                  if(!in_array($semestre, array(1,3,5))){
                      
                  return $this->render('AppFrontOfficeBundle:ModuleLibre:PasdeDroit5e.html.twig');
                 }
                 else{//le semestre est 1,3 ou 5
       /// ici on va regarder l'etat des modules de ce semestre //
                     
              $Modules=$rep->getRepository('AppBackOfficeBundle:ResultatElp') //tableau de modules
                               -> ModuleDeSemestreEtud($codeSemetre,$idUser); 
               
                       echo '<br/>';
                        //echo count($Modules);
                         // '<br/>';
                         
                   foreach ($Modules as $module){
                         $moduleMaxAnnee=$module;
                        $trouve=0;
                       for ($index = 0; $index < count($Modules); $index++){
                           if($moduleMaxAnnee->getElement()->getCode()==$Modules[$index]->getElement()->getCode()){
                              
                               if(( $moduleMaxAnnee->getAnnee())<($Modules[$index]->getAnnee())){
                                   $trouve=1;
                                    $moduleMaxAnnee=$Modules[$index] ;
                               }
                           }
                           
                       }
                      if($trouve==0){
                           $tabModules[]=$module;}
                        else{
                            $tabModules[]=$moduleMaxAnnee;
                            
                        }
                   }

                    $cptValider1=0;
                    $cptNonValide1=0;
             foreach ($tabModules as $module){
                           
                       if($module->getStatus()=='V'){
                         $cptValider1++;
                       }else{
                         $cptNonValide1++;
                          $ModNonvalide1=$module;//on obtient le module non validé
                     }
             } 
                  
             
                         if($cptValider1==3 && $cptNonValide1==1){
                             echo $ModNonvalide1->getElement()->getCode().' <br/>';
                         
                           if($semestre==1 || $semestre==3){
///////////// dans le code ci dessous on remplace le semestre1 par le semestre3  ou sem3 par sem6
                               
                                 $tab1=  str_split($codeSemetre);//transforme $element en tableau 
                                 $i=0;
                                 $sem=0; //variable permettant de stocker mon semestre
                              while (($i < count($tab1)) && ($sem== 0)){//cette clause à des limites pour GEO2S600 qui correspond au semestre 6
                                   $sem = (int)$tab1[$i];
                                   if($sem!=0 && $semestre==1){
                                       $semprochain=3;
                                      $tab1[$i]=3;
                                   }
                                  elseif($sem!=0 && $semestre==3){
                                        $semprochain=5;
                                      $tab1[$i]=5;
                                  }
                                   $i++;
                                 }
                                 
                                 $semLibre= implode($tab1);// code semestre de 5è module
  ///////////////////////////////// fin du code de remplacement du semestre                               
                                 
                                 $ModSemLibre=$rep->getRepository('AppBackOfficeBundle:ResultatElp')//tableau de modules
                                        ->ModuleDeSemestre($semLibre);
                                 $cptd=0;
                                 $sort=0;
                                while($sort==0){
                                    for ($i2 = 0; $i2 < count($ModSemLibre); $i2++) {
                                        
                                     $tabm= str_split($ModSemLibre[$i2]->getCode());
                                     $tabMnv=str_split($ModNonvalide1->getElement()->getCode());
                 
                                     for ($index1 = 0; $index1 < count($tabMnv); $index1++) {
                                         if($tabMnv[$index1]!=$tabm[$index1]){//on cherche le module qui differe d'un seul caractère
                                             $nomSemestre1=$tabm[$index1];
                                             $cptd++;
                                         }
                                         
                                     }
                                     if($cptd==1){
                                         $sort=1;
                                        $libre=$ModSemLibre[$i2];
                                         //echo"5 ème module est ".$ModSemLibre[$i2]->getCode();
                                     }
                                   }
                                 }
          //ce code permet de connnaitre  exactement les 3 modules du semestre prochain
                                 
             for($j=0;$j<count($ModSemLibre);$j++){
                 if($ModSemLibre[$j]->getCode()!=$libre->getCode()){
                    $sementreARendre[]=$ModSemLibre[$j];
                 }
              }
                
              
              return $this->render('AppFrontOfficeBundle:ModuleLibre:Module5.html.twig', 
                      array('moduleLibre'=>$libre,
                             'semestre'=>$semprochain,
                             'moduleSemestre'=>$sementreARendre));
                                /* foreach($ModSemLibre as $libre){
                                    echo $libre->getCode().'<br/>';
                                 }*/
                          }
                    }
                    else{
                        echo'5ème module Refusé !!!<br/>'; 
                    }
                    
                 }
             }
/////////////////////////LE CODE DE   LA SESSION 2 COMMENCE ICI   //////////////////////////////////////////////////////////////           
            
             
             elseif ($session==2){ 
                 
                 if(!in_array($semestre, array(2,4,6))){
                     
                  return $this->render('AppFrontOfficeBundle:ModuleLibre:PasdeDroit5e.html.twig');
                 
                  
                 }
                 else{//le semestre est 2,4 ou 6
       /// ici on va regarder l'etat des modules de ce semestre //
                     
              $Modules=$rep->getRepository('AppBackOfficeBundle:ResultatElp') //tableau de modules
                               -> ModuleDeSemestreEtud($codeSemetre,$idUser); 
               
                       echo '<br/>';
                        //echo count($Modules);
                         // '<br/>';
                         
                   foreach ($Modules as $module){
                         $moduleMaxAnnee=$module;
                        $trouve=0;
                       for ($index = 0; $index < count($Modules); $index++) {
                           if($moduleMaxAnnee->getElement()->getCode()==$Modules[$index]->getElement()->getCode()){
                              
                               if(( $moduleMaxAnnee->getAnnee())<($Modules[$index]->getAnnee())){
                                   $trouve=1;
                                    $moduleMaxAnnee=$Modules[$index] ;
                               }
                           }
                           
                       }
                      if($trouve==0){
                           $tabModules[]=$module;}
                        else{
                            $tabModules[]=$moduleMaxAnnee;
                            
                        }
                   }

                    $cptValider2=0;
                    $cptNonValide2=0;
             foreach ($tabModules as $module){
                           
                       if($module->getStatus()=='V'){
                         $cptValider2++;
                       }else{
                         $cptNonValide2++;
                          $ModNonvalide2=$module;//on obtient le module non validé
                     }
             } 
                  
             
                         if($cptValider2==3 && $cptNonValide2==1){
                             echo $ModNonvalide2->getElement()->getCode().' <br/>';
                         
                           if($semestre==2 || $semestre==4){
///////////// dans le code ci dessous on remplace le semestre1 par le semestre3  ou sem3 par sem6
                               
                                 $tab2=  str_split($codeSemetre);//transforme $element en tableau 
                                 $i=0;
                                 $sem=0; //variable permettant de stocker mon semestre
                              while (($i < count($tab2)) && ($sem== 0)){//cette clause à des limites pour GEO2S600 qui correspond au semestre 6
                                   $sem = (int)$tab2[$i];
                                   if($sem!=0 && $semestre==2){
                                        $semprochain2=4;
                                      $tab2[$i]=4;
                                   }
                                  elseif($sem!=0 && $semestre==4){
                                       $semprochain2=6;
                                      $tab1[$i]=6;
                                  }
                                   $i++;
                                 }
                                 $semLibre= implode($tab2);
  ///////////////////////////////// fin du code de remplacement du semestre                               
                           
                                 $ModSemLibre=$rep->getRepository('AppBackOfficeBundle:ResultatElp')//tableau de modules
                                        ->ModuleDeSemestre($semLibre);
                                 $cptd=0;
                                 $sort=0;
                                while($sort==0){
                                    for ($i2 = 0; $i2 < count($ModSemLibre); $i2++) {
                                        
                                     $tabm= str_split($ModSemLibre[$i2]->getCode());
                                     $tabMnv=str_split($ModNonvalide2->getElement()->getCode());
                                     
               
                                     
                                     
                                     for ($index1 = 0; $index1 < count($tabMnv); $index1++) {
                                         if($tabMnv[$index1]!=$tabm[$index1]){//on cherche le module qui differe d'un seul caractère
                                             $nomSemestre=$tabm[$index1];
                                             $cptd++;
                                         }
                                         
                                     }
                                     if($cptd==1){
                                         $sort=1;
                                        $libre=$ModSemLibre[$i2];
                                         //echo"5 ème module est ".$ModSemLibre[$i2]->getCode();
                                     }
                                   }
                                 }
          //ce code permet de connnaitre  exactement les 3 modules du semestre prochain
                                 
             for($j=0;$j<count($ModSemLibre);$j++){
                 if($ModSemLibre[$j]->getCode()!=$libre->getCode()){
                    $sementreARendre[]=$ModSemLibre[$j];
                 }
              }
                
              
              return $this->render('AppFrontOfficeBundle:ModuleLibre:Module5.html.twig', 
                      array('moduleLibre'=>$libre,
                             'semestre'=> $semprochain2,
                             'moduleSemestre'=>$sementreARendre));
                                /* foreach($ModSemLibre as $libre){
                                    echo $libre->getCode().'<br/>';
                                 }*/
                          }
                    }
                    else{
                  return $this->render('AppFrontOfficeBundle:ModuleLibre:RefuserMod5.html.twig');  
                    }
                    
                 }
               }    
             }                  
     
           /*  public function EnvoyerDemande(Request $request){
                  if ($request->isMethod('POST')) 
                  {
                  $dem5Mode =new Demande();
                  $dem5Mode->setEtudiant($this->getUser())
                          ->setTypeDemande('5M')
                          ;
                    
                    $em = $this->getDoctrine()->getManager();                
                    $em->persist($dem5Mode);
                
                     $em->flush();

                return $this->render('AppFrontOfficeBundle:ModuleLibre:DmdEnvoyer.html.twig');  
                 
                  }
             }*/
}



