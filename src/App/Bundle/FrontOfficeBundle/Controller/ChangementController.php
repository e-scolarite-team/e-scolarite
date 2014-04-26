<?php

namespace App\Bundle\FrontOfficeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use App\Bundle\BackOfficeBundle\Entity\Demande;
use App\Bundle\BackOfficeBundle\Entity\TypeDemande;
use App\Bundle\BackOfficeBundle\Entity\Etudiant;
use App\Bundle\BackOfficeBundle\Entity\EtatDemande;
use App\Bundle\BackOfficeBundle\Entity\Admin;
use App\Bundle\BackOfficeBundle\Form\Data\ImportData;
use App\Bundle\BackOfficeBundle\Form\ImportFormType;


class ChangementController extends Controller {
 
    public function changerAction(){
        
        $em = $this->getDoctrine()->getEntityManager();
        
        $etudiant = $this->getUser();
     
        $typedemande = $this->getDoctrine()->getRepository('AppBackOfficeBundle:TypeDemande')->findOneByCode('CM');
        
        $modules1 = null;
        $modules2 = null;
        
         if($this->get('request')->request->get('envoyer') == "Envoyer" ){   
             
             if($this->get('request')->request->get('module1s')) {
                $modules1 = $this->get('request')->request->get('module1s');
             }                  
             if($this->get('request')->request->get('module2s')) {
                $modules2 = $this->get('request')->request->get('module2s');
             }
             
             $remarque = "";
             if($modules1 != null) { 
                 $remarque = $remarque . "Session 1  : " . $modules1;
             }  
             if($modules2 != null) { 
                 $remarque = $remarque . " /   Session 2  : " . $modules2;
             }
                                  
             $demande = new Demande();
             $demande->setEtudiant($etudiant);
             $demande->setTypeDemande($typedemande);
             $demande->setCreatedAt(new \DateTime());
             $demande->setRemarque($remarque);
             $demande->setStatus(0);
             $demande->setNotified(0);
             $em->persist($demande);
             $etatDemandes = new EtatDemande();
             $etatDemandes->setEtat("en attente");
             $etatDemandes->setDemande($demande);
             $em->persist($etatDemandes);
             $em->flush();
            
             return $this->render( 'AppFrontOfficeBundle:Changement:demandeautorise.html.twig',  array(  'demande' => $demande ) );
            
         }
        
        $da =  new \DateTime();
        $dd = $da->format('Y-m-d');
        $year = substr($dd, 0, 4);
        $month = substr($dd, 5, 2);
        if($month == "09" || $month == "10" || $month == "11" || $month == "12"){
                  $debut =  $year . "-09-01 00:00:00"; 
                  $date_debut = new \DateTime($debut);
                  $fin = ($year + 1) . "-08-30 00:00:00";
                  $date_fin  = new \DateTime($fin);                      
        } elseif ($month == "01" || $month == "02" || $month == "03" || $month == "04" || $month == "05" || $month == "06" || $month == "07" || $month == "08"){
                   $debut =  ($year - 1) . "-09-01 00:00:00"; 
                   $date_debut = new \DateTime($debut);
                   $fin = $year . "-08-30 00:00:00";
                   $date_fin = new \DateTime($fin);
        }
        
        $Demandes = $this->getDoctrine()->getRepository('AppBackOfficeBundle:Demande')->getDemandess ($etudiant, $typedemande, $date_debut, $date_fin);
        $count = count($Demandes);
        if( $count >= $typedemande->getMaxAutorise() ) {
           return $this->render( 'AppFrontOfficeBundle:Changement:counterdepasse.html.twig', array( 'count' => $typedemande->getMaxAutorise() ) );
        }

        $NVModulesForS1S3 = "";
        $NVModulesForS1S3Tab = null;
        $ModulesNotEtudiedS1S3  = "";
        $ModulesNotEtudiedS1S3Tab = null;

        $NVModulesForS2S4 = "";
        $NVModulesForS2S4Tab = null;
        $ModulesNotEtudiedS2S4  = "";
        $ModulesNotEtudiedS2S4Tab = null;


        $NVModulesForS3S5 = "";
        $NVModulesForS3S5Tab = null;
        $ModulesNotEtudiedS3S5  = "";
        $ModulesNotEtudiedS3S5Tab = null;

        $NVModulesForS4S6 = "";
        $NVModulesForS4S6Tab = null;
        $ModulesNotEtudiedS4S6  = "";
        $ModulesNotEtudiedS4S6Tab = null;
           
        $countS3S5M1 = $this->getDoctrine()->getRepository('AppBackOfficeBundle:ResultatElp')->getCountPrerequisiteModules($etudiant, "%3%1%", "%5%1%");
        $countS3S5M2 = $this->getDoctrine()->getRepository('AppBackOfficeBundle:ResultatElp')->getCountPrerequisiteModules($etudiant, "%3%2%", "%5%2%");
        $countS3S5M3 = $this->getDoctrine()->getRepository('AppBackOfficeBundle:ResultatElp')->getCountPrerequisiteModules($etudiant, "%3%3%", "%5%3%");
        $countS3S5M4 = $this->getDoctrine()->getRepository('AppBackOfficeBundle:ResultatElp')->getCountPrerequisiteModules($etudiant, "%3%4%", "%5%4%");

        $maxYear = $this->getDoctrine()->getRepository('AppBackOfficeBundle:ResultatElp')->getMaxYearForEtudiant($etudiant);

      
        $somme = 0; 
        if ( count($countS3S5M1) == 2 )  { 
           $somme +=  2;          
        }
        if ( count($countS3S5M2) == 2 )  { 
           $somme +=  2;          
        }
        if ( count($countS3S5M3) == 2 )  { 
           $somme +=  2;          
        }
        if ( count($countS3S5M4) == 2 )  { 
            $somme +=  2;          
        }

        if($somme == 2 || $somme == 4){
            
            $NVModulesForS3S5 = $this->getDoctrine()->getRepository('AppBackOfficeBundle:ResultatElp')
            ->getNVModulesInLastYearforEtudiant($etudiant, $maxYear, "%3%1%", "%3%2%", "%3%3%", "%3%4%", "%5%1%", "%5%2%", "%5%3%", "%5%4%");
                
            foreach($NVModulesForS3S5 as $m){
                $NVModulesForS3S5Tab[] = $m->getElement()->getCode();
            }
            
            $VModulesForS3S5 = $this->getDoctrine()->getRepository('AppBackOfficeBundle:ResultatElp')
            ->getVModulesforEtudiant($etudiant, "%3%1%", "%3%2%", "%3%3%", "%3%4%", "%5%1%", "%5%2%", "%5%3%", "%5%4%");
            
            foreach($VModulesForS3S5 as $m){
                $VModulesForS3S5Tab[] = $m->getElement()->getCode();
            }
            
            $pos3 = $this->getDoctrine()->getRepository('AppBackOfficeBundle:ResultatElp')
            -> getPositionOf("3", $etudiant, "%3%1%", "%3%2%", "%3%3%", "%3%4%", "%5%1%", "%5%2%", "%5%3%", "%5%4%");
            $pos5 = $this->getDoctrine()->getRepository('AppBackOfficeBundle:ResultatElp')
            -> getPositionOf("5", $etudiant, "%3%1%", "%3%2%", "%3%3%", "%3%4%", "%5%1%", "%5%2%", "%5%3%", "%5%4%");

            if($pos5 < $pos3 ){
                $pos = $pos5;
            } else {
                $pos = $pos3;
            }
            if($pos5 == 0 & $pos == 0){
                $pos = $pos3;
            }
            if($pos3 == 0 & $pos == 0){
                $pos = $pos5;
            }
            $pos--;
            
            $filiere = $this->getDoctrine()->getRepository('AppBackOfficeBundle:ResultatElp')-> getFiliere($pos, $etudiant);
            
            $ModulesNotEtudiedS3S5 = $this->getDoctrine()->getRepository('AppBackOfficeBundle:ElementPedagogi')
            ->getNotEtudiedModules($filiere, $NVModulesForS3S5Tab, $VModulesForS3S5Tab, "%3%1%", "%3%2%", "%3%3%", "%3%4%", "%5%1%", "%5%2%", "%5%3%", "%5%4%");
            foreach($ModulesNotEtudiedS3S5 as $m){
                $ModulesNotEtudiedS3S5Tab[] = $m->getCode();
            }
        }
        
        $countS4S6M1 = $this->getDoctrine()->getRepository('AppBackOfficeBundle:ResultatElp')->getCountPrerequisiteModules($etudiant, "%4%1%", "%6%1%");
        $countS4S6M2 = $this->getDoctrine()->getRepository('AppBackOfficeBundle:ResultatElp')->getCountPrerequisiteModules($etudiant, "%4%2%", "%6%2%");
        $countS4S6M3 = $this->getDoctrine()->getRepository('AppBackOfficeBundle:ResultatElp')->getCountPrerequisiteModules($etudiant, "%4%3%", "%6%3%");
        $countS4S6M4 = $this->getDoctrine()->getRepository('AppBackOfficeBundle:ResultatElp')->getCountPrerequisiteModules($etudiant, "%4%4%", "%6%4%");
        
        $somme = 0; 
        if ( count($countS4S6M1) == 2 )  { 
            $somme +=  2;          
        }
        if ( count($countS4S6M2) == 2 )  { 
            $somme +=  2;          
        }
        if ( count($countS4S6M3) == 2 )  { 
            $somme +=  2;          
        }
        if ( count($countS4S6M4) == 2 )  { 
            $somme +=  2;          
        }
       
        if($somme == 2 or $somme == 4){
            
            $NVModulesForS4S6 = $this->getDoctrine()->getRepository('AppBackOfficeBundle:ResultatElp')
            ->getNVModulesInLastYearforEtudiant($etudiant, $maxYear, "%4%1%", "%4%2%", "%4%3%", "%4%4%", "%6%1%", "%6%2%", "%6%3%", "%6%4%");
            
            foreach($NVModulesForS4S6 as $m){
                $NVModulesForS4S6Tab[] = $m->getElement()->getCode();
            }
            
            $VModulesForS4S6 = $this->getDoctrine()->getRepository('AppBackOfficeBundle:ResultatElp')
            ->getVModulesforEtudiant($etudiant, "%4%1%", "%4%2%", "%4%3%", "%4%4%", "%6%1%", "%6%2%", "%6%3%", "%6%4%");
            
            foreach($VModulesForS4S6 as $m){
                $VModulesForS4S6Tab[] = $m->getElement()->getCode();
            }
           
            $pos4 = $this->getDoctrine()->getRepository('AppBackOfficeBundle:ResultatElp')
            -> getPositionOf("4", $etudiant, "%4%1%", "%4%2%", "%4%3%", "%4%4%", "%6%1%", "%6%2%", "%6%3%", "%6%4%");
            $pos6 = $this->getDoctrine()->getRepository('AppBackOfficeBundle:ResultatElp')
            -> getPositionOf("6", $etudiant, "%4%1%", "%4%2%", "%4%3%", "%4%4%", "%6%1%", "%6%2%", "%6%3%", "%6%4%");
            
            if($pos6 < $pos4 ){
                $pos = $pos6;
            } else {
                $pos = $pos4;
            }
            if($pos6 == 0 & $pos == 0){
                $pos = $pos4;
            }
            if($pos4 == 0 & $pos == 0){
                $pos = $pos6;
            }
            $pos--;
            $filiere = $this->getDoctrine()->getRepository('AppBackOfficeBundle:ResultatElp')-> getFiliere($pos, $etudiant);
             //return new Response(var_dump($filiere));
            $ModulesNotEtudiedS4S6 = $this->getDoctrine()->getRepository('AppBackOfficeBundle:ElementPedagogi')
            ->getNotEtudiedModules($filiere, $NVModulesForS4S6Tab, $VModulesForS4S6Tab, "%4%1%", "%4%2%", "%4%3%", "%4%4%", "%6%1%", "%6%2%", "%6%3%", "%6%4%");
            
            foreach($ModulesNotEtudiedS4S6 as $m){
                $ModulesNotEtudiedS4S6Tab[] = $m->getCode();
            }
            
            $CountNotS6M3M4= $this->getDoctrine()->getRepository('AppBackOfficeBundle:ElementPedagogi')
            ->getCountNotEtudiedModulesS6M3M4($filiere, $NVModulesForS4S6Tab, $VModulesForS4S6Tab, "%6%3%", "%6%4%");
            
            $countVS = $this->getDoctrine()->getRepository('AppBackOfficeBundle:ResultatElp')->getVSemestreNumber($etudiant);
            if( ($CountNotS6M3M4 == 1 | $CountNotS6M3M4 == 2) & $countVS < 4)  {
                 return $this->render( 'AppFrontOfficeBundle:Changement:checkvalide.html.twig',  array( ) );
            }
        }
              
        if(count($NVModulesForS3S5Tab) + count($ModulesNotEtudiedS3S5Tab) > 4 | count($NVModulesForS4S6Tab) + count($ModulesNotEtudiedS4S6Tab) > 4){

                   return $this->render('AppFrontOfficeBundle:Changement:changer.html.twig', 
                                         array( 
                                                "modules_non_validerS1S3" => $NVModulesForS1S3 ,
                                                "modules_non_etudierS1S3" => $ModulesNotEtudiedS1S3 ,
                                                "modules_non_validerS2S4" => $NVModulesForS2S4 ,
                                                "modules_non_etudierS2S4" => $ModulesNotEtudiedS2S4 ,
                                                "modules_non_validerS3S5" => $NVModulesForS3S5 ,
                                                "modules_non_etudierS3S5" => $ModulesNotEtudiedS3S5 ,
                                                "modules_non_validerS4S6" => $NVModulesForS4S6 ,
                                                "modules_non_etudierS4S6" => $ModulesNotEtudiedS4S6
                                              ));
         }
         
        $countS1S3M1 = $this->getDoctrine()->getRepository('AppBackOfficeBundle:ResultatElp')->getCountPrerequisiteModules($etudiant, "%1%1%", "%3%1%");
        $countS1S3M2 = $this->getDoctrine()->getRepository('AppBackOfficeBundle:ResultatElp')->getCountPrerequisiteModules($etudiant, "%1%2%", "%3%2%");
        $countS1S3M3 = $this->getDoctrine()->getRepository('AppBackOfficeBundle:ResultatElp')->getCountPrerequisiteModules($etudiant, "%1%3%", "%3%3%");
        $countS1S3M4 = $this->getDoctrine()->getRepository('AppBackOfficeBundle:ResultatElp')->getCountPrerequisiteModules($etudiant, "%1%4%", "%3%4%");

        $somme = 0; 
        if ( count($countS1S3M1) == 2 )  { 
            $somme +=  2;          
        }
        if ( count($countS1S3M2) == 2 )  { 
            $somme +=  2;          
        }
        if ( count($countS1S3M3) == 2 )  { 
            $somme +=  2;          
        }
        if ( count($countS1S3M4) == 2 )  { 
            $somme +=  2;          
        }

        if($somme == 2 or $somme == 4){
             
            $NVModulesForS1S3 = $this->getDoctrine()->getRepository('AppBackOfficeBundle:ResultatElp')
            ->getNVModulesInLastYearforEtudiant($etudiant, $maxYear, "%1%1%", "%1%2%", "%1%3%", "%1%4%", "%3%1%", "%3%2%", "%3%3%", "%3%4%");

            foreach($NVModulesForS1S3 as $m){
                $NVModulesForS1S3Tab[] = $m->getElement()->getCode();
            }  
            
            $VModulesForS1S3 = $this->getDoctrine()->getRepository('AppBackOfficeBundle:ResultatElp')
            ->getVModulesforEtudiant($etudiant, "%1%1%", "%1%2%", "%1%3%", "%1%4%", "%3%1%", "%3%2%", "%3%3%", "%3%4%");

            foreach($VModulesForS1S3 as $m){ 
                $VModulesForS1S3Tab[] = $m->getElement()->getCode();
            }
            
            $pos1 = $this->getDoctrine()->getRepository('AppBackOfficeBundle:ResultatElp')
            -> getPositionOf("1", $etudiant, "%1%1%", "%1%2%", "%1%3%", "%1%4%", "%3%1%", "%3%2%", "%3%3%", "%3%4%");
            $pos3 = $this->getDoctrine()->getRepository('AppBackOfficeBundle:ResultatElp')
            -> getPositionOf("3", $etudiant, "%1%1%", "%1%2%", "%1%3%", "%1%4%", "%3%1%", "%3%2%", "%3%3%", "%3%4%");
            
            if($pos3 < $pos1 ){
                $pos = $pos3;
            } else {
                $pos = $pos1;
            }
            if($pos3 == 0 & $pos == 0){
                $pos = $pos1;
            }
            if($pos1 == 0 & $pos == 0){
                $pos = $pos3;
            }
            $pos--;
            $filiere = $this->getDoctrine()->getRepository('AppBackOfficeBundle:ResultatElp')-> getFiliere($pos, $etudiant);
            $ModulesNotEtudiedS1S3 = $this->getDoctrine()->getRepository('AppBackOfficeBundle:ElementPedagogi')
            ->getNotEtudiedModules($filiere, $NVModulesForS1S3Tab, $VModulesForS1S3Tab, "%1%1%", "%1%2%", "%1%3%", "%1%4%", "%3%1%", "%3%2%", "%3%3%", "%3%4%");
            
            foreach($ModulesNotEtudiedS1S3 as $m){
                $ModulesNotEtudiedS1S3Tab[] = $m->getCode();
            }
        }
        
        $countS2S4M1 = $this->getDoctrine()->getRepository('AppBackOfficeBundle:ResultatElp')->getCountPrerequisiteModules($etudiant, "%2%1%", "%4%1%");
        $countS2S4M2 = $this->getDoctrine()->getRepository('AppBackOfficeBundle:ResultatElp')->getCountPrerequisiteModules($etudiant, "%2%2%", "%4%2%");
        $countS2S4M3 = $this->getDoctrine()->getRepository('AppBackOfficeBundle:ResultatElp')->getCountPrerequisiteModules($etudiant, "%2%3%", "%4%3%");
        $countS2S4M4 = $this->getDoctrine()->getRepository('AppBackOfficeBundle:ResultatElp')->getCountPrerequisiteModules($etudiant, "%2%4%", "%4%4%");

        $somme = 0; 
        if ( count($countS2S4M1) == 2 )  { 
            $somme +=  2;          
        }
        if ( count($countS2S4M2) == 2 )  { 
            $somme +=  2;          
        }
        if ( count($countS2S4M3) == 2 )  { 
            $somme +=  2;          
        }
        if ( count($countS2S4M4) == 2 )  { 
            $somme +=  2;          
        }
        if($somme == 2 or $somme == 4){ 
            
            $NVModulesForS2S4 = $this->getDoctrine()->getRepository('AppBackOfficeBundle:ResultatElp')
            ->getNVModulesInLastYearforEtudiant($etudiant, $maxYear, "%2%1%", "%2%2%", "%2%3%", "%2%4%", "%4%1%", "%4%2%", "%4%3%", "%4%4%");

            foreach($NVModulesForS2S4 as $m){
                $NVModulesForS2S4Tab[] = $m->getElement()->getCode();
            }

            $VModulesForS2S4 = $this->getDoctrine()->getRepository('AppBackOfficeBundle:ResultatElp')
            ->getVModulesforEtudiant($etudiant, "%2%1%", "%2%2%", "%2%3%", "%2%4%", "%4%1%", "%4%2%", "%4%3%", "%4%4%");

            foreach($VModulesForS2S4 as $m){ 
               $VModulesForS2S4Tab[] = $m->getElement()->getCode();
            }

            $pos2 = $this->getDoctrine()->getRepository('AppBackOfficeBundle:ResultatElp')
            -> getPositionOf("2", $etudiant, "%2%1%", "%2%2%", "%2%3%", "%2%4%", "%4%1%", "%4%2%", "%4%3%", "%4%4%");

            $pos4 = $this->getDoctrine()->getRepository('AppBackOfficeBundle:ResultatElp')
            -> getPositionOf("4", $etudiant, "%2%1%", "%2%2%", "%2%3%", "%2%4%", "%4%1%", "%4%2%", "%4%3%", "%4%4%");

            if($pos4 < $pos2 ){
                $pos = $pos4;
            } else {
                $pos = $pos2;
            }
            if($pos4 == 0 & $pos == 0){
                $pos = $pos2;
            }
            if($pos2 == 0 & $pos == 0){
                $pos = $pos4;
            }
            $pos--;

            $filiere = $this->getDoctrine()->getRepository('AppBackOfficeBundle:ResultatElp')-> getFiliere($pos, $etudiant);

            $ModulesNotEtudiedS2S4 = $this->getDoctrine()->getRepository('AppBackOfficeBundle:ElementPedagogi')
            ->getNotEtudiedModules($filiere, $NVModulesForS2S4Tab, $VModulesForS2S4Tab, "%2%1%", "%2%2%", "%2%3%", "%2%4%", "%4%1%", "%4%2%", "%4%3%", "%4%4%");

            foreach($ModulesNotEtudiedS2S4 as $m){
                $ModulesNotEtudiedS2S4Tab[] = $m->getCode();
            }
        }
        
        if(count($NVModulesForS1S3Tab) + count($ModulesNotEtudiedS1S3Tab) > 4 | count($NVModulesForS2S4Tab) + count($ModulesNotEtudiedS2S4Tab) > 4){
            return $this->render('AppFrontOfficeBundle:Changement:changer.html.twig', 
                                  array( 
                                         "modules_non_validerS1S3" => $NVModulesForS1S3 ,
                                         "modules_non_etudierS1S3" => $ModulesNotEtudiedS1S3 ,
                                         "modules_non_validerS2S4" => $NVModulesForS2S4 ,
                                         "modules_non_etudierS2S4" => $ModulesNotEtudiedS2S4 ,
                                         "modules_non_validerS3S5" => $NVModulesForS3S5 ,
                                         "modules_non_etudierS3S5" => $ModulesNotEtudiedS3S5 ,
                                         "modules_non_validerS4S6" => $NVModulesForS4S6 ,
                                         "modules_non_etudierS4S6" => $ModulesNotEtudiedS4S6
                                       ));
        }
        return $this->render('AppFrontOfficeBundle:Changement:pasdemoduleachanger.html.twig', array());
    }
}
