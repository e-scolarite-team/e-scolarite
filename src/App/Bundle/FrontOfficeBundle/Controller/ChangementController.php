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
        $repEtudiant = $this->getDoctrine()->getRepository('AppBackOfficeBundle:Etudiant');
        $etudiant = $repEtudiant->findOneById(4);
        
        $repTypeDemande = $this->getDoctrine()->getRepository('AppBackOfficeBundle:TypeDemande');
        $typedemande = $repTypeDemande->findOneByCode('CM');
        
        $modules1 = null;
        $modules2 = null;
        
         if($this->get('request')->request->get('envoyer') == "Envoyer" ){   
                       
              //return new Response(var_dump($typedemande));
              
             $demande = new Demande();
             $demande->setEtudiant($etudiant);
             $demande->setTypeDemande($typedemande);
             $demande->setCreatedAt(new \DateTime());
             $d =  $demande->getCreatedAt()->format('Y-m-d');
             $year = substr($d, 0, 4);
             $month = substr($d, 5, 2);
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
             
            $qb = $em->createQueryBuilder();
            $qb->select('d')
            ->from('App\Bundle\BackOfficeBundle\Entity\Demande', 'd')
            ->where($qb->expr()->eq('d.etudiant', '?1'))
            ->andWhere($qb->expr()->eq('d.typeDemande', '?2'))
            ->andWhere($qb->expr()->neq('d.status', '?5'))
            ->andWhere($qb->expr()->between('d.createdAt', '?3', '?4'))
            ->setParameter(1, $etudiant)
            ->setParameter(2, $typedemande)
            ->setParameter(3, $date_debut)
            ->setParameter(4, $date_fin)
            ->setParameter(5, 2);

            $Demandes = $qb->getQuery()->getResult();
           
            $count = count($Demandes);
            if( $count >= $typedemande->getMaxAutorise() ){
                return $this->render(
                                'AppFrontOfficeBundle:Changement:countdepasse.html.twig', 
                                array( 'count' => $typedemande->getMaxAutorise(),  'demande' => $demande )
                            );
            }
            //return new Response(var_dump($count));
            
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
             $demande->setRemarque($remarque);
             $demande->setStatus(0);
             $demande->setNotified(0);
             $em->persist($demande);
             $etatDemandes =new EtatDemande();
             $etatDemandes->setEtat("en attente");
             $etatDemandes->setDemande($demande);
             $em->persist($etatDemandes);
             $em->flush();
            
             return $this->render(
                                'AppFrontOfficeBundle:Changement:demandeautorise.html.twig', 
                                 array(  'demande' => $demande )
                            );
             //return new Response(var_dump($modules3));
             
             

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
             
            $qb = $em->createQueryBuilder();
            $qb->select('d')
            ->from('App\Bundle\BackOfficeBundle\Entity\Demande', 'd')
            ->where($qb->expr()->eq('d.etudiant', '?1'))
            ->andWhere($qb->expr()->eq('d.typeDemande', '?2'))
            ->andWhere($qb->expr()->neq('d.status', '?5'))
            ->andWhere($qb->expr()->between('d.createdAt', '?3', '?4'))
            ->setParameter(1, $etudiant)
            ->setParameter(2, $typedemande)
            ->setParameter(3, $date_debut)
            ->setParameter(4, $date_fin)
            ->setParameter(5, 2);

            $Demandes = $qb->getQuery()->getResult();
           
            $count = count($Demandes);
            // return new Response(var_dump($count));
            if( $count >= $typedemande->getMaxAutorise() ){
                return $this->render(
                                'AppFrontOfficeBundle:Changement:counterdepasse.html.twig', 
                                array( 'count' => $typedemande->getMaxAutorise() )
                            );
            }
                
                $modules_non_valider_pour_der_anneeS1S3 = "";
                $module_non_validerS1S3 = null;
                $module_non_etudierS1S3  = "";
                $modules_non_etudierS1S3 = null;

                $modules_non_valider_pour_der_anneeS2S4 = "";
                $module_non_validerS2S4 = null;
                $module_non_etudierS2S4  = "";
                $modules_non_etudierS2S4 = null;


                $modules_non_valider_pour_der_anneeS3S5 = "";
                $module_non_validerS3S5 = null;
                $module_non_etudierS3S5  = "";
                $modules_non_etudierS3S5 = null;

                $modules_non_valider_pour_der_anneeS4S6 = "";
                $module_non_validerS4S6 = null;
                $module_non_etudierS4S6  = "";
                $modules_non_etudierS4S6 = null;


                // --------------- Tous les Modules Valider 
                $qb = $em->createQueryBuilder();
                $qb->select('r', 'e')
                ->from('App\Bundle\BackOfficeBundle\Entity\ResultatElp', 'r')
                ->leftJoin('r.element', 'e')
                ->Where($qb->expr()->eq('r.status', '?1'))
                ->andWhere($qb->expr()->eq('r.etudiant', '?2'))
                ->andWhere($qb->expr()->eq('e.nature', '?3'))
                ->setParameter(1, "V")
                ->setParameter(2, $etudiant)
                ->setParameter(3, "MOD");  
                $tous_les_modules_valider = $qb->getQuery()->getResult();


                // ------------- est ce que  prer module 1 valider 2---> OUI

                $Qr = $em->createQueryBuilder();
                $Qr->select('r', 'e')
                ->from('App\Bundle\BackOfficeBundle\Entity\ResultatElp', 'r')
                ->leftJoin('r.element', 'e')
                ->Where($Qr->expr()->like('e.code', '?4'))
                ->orWhere($Qr->expr()->like('e.code', '?5'))
                ->andWhere($Qr->expr()->eq('r.status', '?1'))
                ->andWhere($Qr->expr()->eq('r.etudiant', '?2'))
                ->andWhere($Qr->expr()->eq('e.nature', '?3'))
                ->setParameter(1, "V")
                ->setParameter(2, $etudiant)
                ->setParameter(3, "MOD")
                ->setParameter(4, "%3%1%")
                ->setParameter(5, "%5%1%");  
                $modules_prerequisS3S5M1 = $Qr->getQuery()->getResult();
               
                 // ----------------- est ce que  prer module 2 valider 2---> OUI
                $Qr = $em->createQueryBuilder();
                $Qr->select('r', 'e')
                ->from('App\Bundle\BackOfficeBundle\Entity\ResultatElp', 'r')
                ->leftJoin('r.element', 'e')
                ->Where($Qr->expr()->like('e.code', '?4'))
                ->orWhere($Qr->expr()->like('e.code', '?5'))
                ->andWhere($Qr->expr()->eq('r.status', '?1'))
                ->andWhere($Qr->expr()->eq('r.etudiant', '?2'))
                ->andWhere($Qr->expr()->eq('e.nature', '?3'))
                ->setParameter(1, "V")
                ->setParameter(2, $etudiant)
                ->setParameter(3, "MOD")
                ->setParameter(4, "%3%2%")
                ->setParameter(5, "%5%2%");  
                $modules_prerequisS3S5M2 = $Qr->getQuery()->getResult();
                

                 // ------------------- est ce que  prer module 3 valider 2---> OUI
                $Qr = $em->createQueryBuilder();
                $Qr->select('r', 'e')
                ->from('App\Bundle\BackOfficeBundle\Entity\ResultatElp', 'r')
                ->leftJoin('r.element', 'e')
                ->Where($Qr->expr()->like('e.code', '?4'))
                ->orWhere($Qr->expr()->like('e.code', '?5'))
                ->andWhere($Qr->expr()->eq('r.status', '?1'))
                ->andWhere($Qr->expr()->eq('r.etudiant', '?2'))
                ->andWhere($Qr->expr()->eq('e.nature', '?3'))
                ->setParameter(1, "V")
                ->setParameter(2, $etudiant)
                ->setParameter(3, "MOD")
                ->setParameter(4, "%3%3%")
                ->setParameter(5, "%5%3%");  
                $modules_prerequisS3S5M3 = $Qr->getQuery()->getResult();

                 // ---------------------- est ce que  prer module 4 valider 2---> OUI
                $Qr = $em->createQueryBuilder();
                $Qr->select('r', 'e')
                ->from('App\Bundle\BackOfficeBundle\Entity\ResultatElp', 'r')
                ->leftJoin('r.element', 'e')
                ->Where($Qr->expr()->like('e.code', '?4'))
                ->orWhere($Qr->expr()->like('e.code', '?5'))
                ->andWhere($Qr->expr()->eq('r.status', '?1'))
                ->andWhere($Qr->expr()->eq('r.etudiant', '?2'))
                ->andWhere($Qr->expr()->eq('e.nature', '?3'))
                ->setParameter(1, "V")
                ->setParameter(2, $etudiant)
                ->setParameter(3, "MOD")
                ->setParameter(4, "%3%4%")
                ->setParameter(5, "%5%4%");
                $modules_prerequisS3S5M4 = $Qr->getQuery()->getResult();
                //return new Response(var_dump($modules_prerequisS3S5M4));
                
               $somme = 0; 
               if ( count($modules_prerequisS3S5M1) == 2 )  { 
                   $somme +=  2;          
               }
               if ( count($modules_prerequisS3S5M2) == 2 )  { 
                   $somme +=  2;          
               }
               if ( count($modules_prerequisS3S5M3) == 2 )  { 
                   $somme +=  2;          
               }
               if ( count($modules_prerequisS3S5M4) == 2 )  { 
                   $somme +=  2;          
               }
                  //return new Response(var_dump($somme));
                // return new Response(var_dump($somme));
                if($somme == 2 || $somme == 4){
                    // ------------------------------------  Max year 
                    $qb = $em->createQueryBuilder();
                    $qb->select('r')
                    ->addSelect($qb->expr()->max('r.annee'))
                    ->from('App\Bundle\BackOfficeBundle\Entity\ResultatElp', 'r')
                    ->andWhere($qb->expr()->eq('r.etudiant', '?2'))
                    ->setParameter(2, $etudiant);
                    $max = $qb->getQuery()->getResult();
                    $max_yearS3S5 = (int) $max[0][1];
                    //return new Response(var_dump($max_yearS3S5));


                    // ------------------------   Modules non valider pour la derniere annee
                    $Qr = $em->createQueryBuilder();
                    $Qr->select('r', 'e')
                    ->from('App\Bundle\BackOfficeBundle\Entity\ResultatElp', 'r')
                    ->leftJoin('r.element', 'e')
                    ->Where($Qr->expr()->like('e.code', '?4'))
                    ->orWhere($Qr->expr()->like('e.code', '?5'))
                    ->orWhere($Qr->expr()->like('e.code', '?6'))
                    ->orWhere($Qr->expr()->like('e.code', '?7'))
                    ->orWhere($Qr->expr()->like('e.code', '?8'))
                    ->orWhere($Qr->expr()->like('e.code', '?9'))
                    ->orWhere($Qr->expr()->like('e.code', '?10'))
                    ->orWhere($Qr->expr()->like('e.code', '?11'))
                    ->andWhere($Qr->expr()->eq('r.status', '?1'))
                    ->andWhere($Qr->expr()->eq('r.etudiant', '?2'))
                    ->andWhere($Qr->expr()->eq('e.nature', '?3'))
                    ->andWhere($Qr->expr()->eq('r.annee', '?12'))
                    ->setParameter(1, "NV")
                    ->setParameter(2, $etudiant)
                    ->setParameter(3, "MOD")
                    ->setParameter(4, "%3%1%")
                    ->setParameter(5, "%3%2%")
                    ->setParameter(6, "%3%3%")
                    ->setParameter(7, "%3%4%")
                    ->setParameter(8, "%5%1%")
                    ->setParameter(9, "%5%2%")
                    ->setParameter(10, "%5%3%")
                    ->setParameter(11, "%5%4%")
                    ->setParameter(12, $max_yearS3S5);
                    $modules_non_valider_pour_der_anneeS3S5 = $Qr->getQuery()->getResult();

                    foreach($modules_non_valider_pour_der_anneeS3S5 as $m){
                        $module_non_validerS3S5[] = $m->getElement()->getCode();
                    }

                    // ------------- Module valider pour S1S3
                    $Qr = $em->createQueryBuilder();
                    $Qr->select('r', 'e')
                    ->from('App\Bundle\BackOfficeBundle\Entity\ResultatElp', 'r')
                    ->leftJoin('r.element', 'e')
                    ->Where($Qr->expr()->like('e.code', '?4'))
                    ->orWhere($Qr->expr()->like('e.code', '?5'))
                    ->orWhere($Qr->expr()->like('e.code', '?6'))
                    ->orWhere($Qr->expr()->like('e.code', '?7'))
                    ->orWhere($Qr->expr()->like('e.code', '?8'))
                    ->orWhere($Qr->expr()->like('e.code', '?9'))
                    ->orWhere($Qr->expr()->like('e.code', '?10'))
                    ->orWhere($Qr->expr()->like('e.code', '?11'))
                    ->andWhere($Qr->expr()->eq('r.status', '?1'))
                    ->andWhere($Qr->expr()->eq('r.etudiant', '?2'))
                    ->andWhere($Qr->expr()->eq('e.nature', '?3'))
                    ->setParameter(1, "V")
                    ->setParameter(2, $etudiant)
                    ->setParameter(3, "MOD")
                    ->setParameter(4, "%3%1%")
                    ->setParameter(5, "%3%2%")
                    ->setParameter(6, "%3%3%")
                    ->setParameter(7, "%3%4%")
                    ->setParameter(8, "%5%1%")
                    ->setParameter(9, "%5%2%")
                    ->setParameter(10, "%5%3%")
                    ->setParameter(11, "%5%4%");
                    $modules_valider_S3S5 = $Qr->getQuery()->getResult();

                    foreach($modules_valider_S3S5 as $m){
                        $module_validerS3S5[] = $m->getElement()->getCode();
                    }

                    // -------------- Position de prmier 1 
                    $Qr = $em->createQueryBuilder();
                    $Qr->select('r', 'e')
                    ->addSelect("MAX(LOCATE('3', e.code))")
                    ->from('App\Bundle\BackOfficeBundle\Entity\ResultatElp', 'r')
                    ->leftJoin('r.element', 'e')
                    ->Where($Qr->expr()->like('e.code', '?4'))
                    ->orWhere($Qr->expr()->like('e.code', '?5'))
                    ->orWhere($Qr->expr()->like('e.code', '?6'))
                    ->orWhere($Qr->expr()->like('e.code', '?7'))
                    ->orWhere($Qr->expr()->like('e.code', '?8'))
                    ->orWhere($Qr->expr()->like('e.code', '?9'))
                    ->orWhere($Qr->expr()->like('e.code', '?10'))
                    ->orWhere($Qr->expr()->like('e.code', '?11'))
                    ->andWhere($Qr->expr()->eq('r.status', '?1'))
                    ->andWhere($Qr->expr()->eq('r.etudiant', '?2'))
                    ->andWhere($Qr->expr()->eq('e.nature', '?3'))
                    ->setParameter(1, "V")
                    ->setParameter(2, $etudiant)
                    ->setParameter(3, "MOD")
                    ->setParameter(4, "%3%1%")
                    ->setParameter(5, "%3%2%")
                    ->setParameter(6, "%3%3%")
                    ->setParameter(7, "%3%4%")
                    ->setParameter(8, "%5%1%")
                    ->setParameter(9, "%5%2%")
                    ->setParameter(10, "%5%3%")
                    ->setParameter(11, "%5%4%");
                    $positionof3 = $Qr->getQuery()->getResult();
                    $pos3 = (int) $positionof3[0][1];

                     // -------------- Position de prmier 3 
                    $Qr = $em->createQueryBuilder();
                    $Qr->select('r', 'e')
                    ->addSelect("MAX(LOCATE('5', e.code))")
                    ->from('App\Bundle\BackOfficeBundle\Entity\ResultatElp', 'r')
                    ->leftJoin('r.element', 'e')
                    ->Where($Qr->expr()->like('e.code', '?4'))
                    ->orWhere($Qr->expr()->like('e.code', '?5'))
                    ->orWhere($Qr->expr()->like('e.code', '?6'))
                    ->orWhere($Qr->expr()->like('e.code', '?7'))
                    ->orWhere($Qr->expr()->like('e.code', '?8'))
                    ->orWhere($Qr->expr()->like('e.code', '?9'))
                    ->orWhere($Qr->expr()->like('e.code', '?10'))
                    ->orWhere($Qr->expr()->like('e.code', '?11'))
                    ->andWhere($Qr->expr()->eq('r.status', '?1'))
                    ->andWhere($Qr->expr()->eq('r.etudiant', '?2'))
                    ->andWhere($Qr->expr()->eq('e.nature', '?3'))
                    ->setParameter(1, "V")
                    ->setParameter(2, $etudiant)
                    ->setParameter(3, "MOD")
                    ->setParameter(4, "%3%1%")
                    ->setParameter(5, "%3%2%")
                    ->setParameter(6, "%3%3%")
                    ->setParameter(7, "%3%4%")
                    ->setParameter(8, "%5%1%")
                    ->setParameter(9, "%5%2%")
                    ->setParameter(10, "%5%3%")
                    ->setParameter(11, "%5%4%");
                    $positionof5 = $Qr->getQuery()->getResult();
                    $pos5 = (int) $positionof5[0][1];


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

                    //    get filier 
                    $Qr = $em->createQueryBuilder();
                    $Qr->select('r', 'e')
                    ->addSelect("SUBSTRING(e.code, 1, $pos)")
                    ->from('App\Bundle\BackOfficeBundle\Entity\ResultatElp', 'r')
                    ->leftJoin('r.element', 'e')
                    ->Where($Qr->expr()->like('e.code', '?4'))
                    ->orWhere($Qr->expr()->like('e.code', '?5'))
                    ->orWhere($Qr->expr()->like('e.code', '?6'))
                    ->orWhere($Qr->expr()->like('e.code', '?7'))
                    ->orWhere($Qr->expr()->like('e.code', '?8'))
                    ->orWhere($Qr->expr()->like('e.code', '?9'))
                    ->orWhere($Qr->expr()->like('e.code', '?10'))
                    ->orWhere($Qr->expr()->like('e.code', '?11'))
                    ->andWhere($Qr->expr()->eq('r.status', '?1'))
                    ->andWhere($Qr->expr()->eq('r.etudiant', '?2'))
                    ->andWhere($Qr->expr()->eq('e.nature', '?3'))
                    ->setParameter(1, "V")
                    ->setParameter(2, $etudiant)
                    ->setParameter(3, "MOD")
                    ->setParameter(4, "%3%1%")
                    ->setParameter(5, "%3%2%")
                    ->setParameter(6, "%3%3%")
                    ->setParameter(7, "%3%4%")
                    ->setParameter(8, "%5%1%")
                    ->setParameter(9, "%5%2%")
                    ->setParameter(10, "%5%3%")
                    ->setParameter(11, "%5%4%");
                    $FLR = $Qr->getQuery()->getResult();
                    $flr = $FLR[0][1];
                    $filiere = $flr . '%';

                    // MODULE NON Etudier 
                    $Qr = $em->createQueryBuilder();
                    $Qr->select('e')
                    ->from('App\Bundle\BackOfficeBundle\Entity\ElementPedagogi', 'e')
                    ->Where($Qr->expr()->like('e.code', '?4'))
                    ->orWhere($Qr->expr()->like('e.code', '?5'))
                    ->orWhere($Qr->expr()->like('e.code', '?6'))
                    ->orWhere($Qr->expr()->like('e.code', '?7'))
                    ->orWhere($Qr->expr()->like('e.code', '?8'))
                    ->orWhere($Qr->expr()->like('e.code', '?9'))
                    ->orWhere($Qr->expr()->like('e.code', '?10'))
                    ->orWhere($Qr->expr()->like('e.code', '?11'))
                    ->andWhere($Qr->expr()->eq('e.nature', '?3'))
                    ->andWhere($Qr->expr()->like('e.code', '?1'))
                    ->andWhere($Qr->expr()->notIn('e.code', '?12'))
                    ->andWhere($Qr->expr()->notIn('e.code', '?13'))
                    ->setParameter(1, $filiere)
                    ->setParameter(12, $module_non_validerS3S5)
                    ->setParameter(13, $module_validerS3S5)
                    ->setParameter(3, "MOD")
                    ->setParameter(4, "%3%1%")
                    ->setParameter(5, "%3%2%")
                    ->setParameter(6, "%3%3%")
                    ->setParameter(7, "%3%4%")
                    ->setParameter(8, "%5%1%")
                    ->setParameter(9, "%5%2%")
                    ->setParameter(10, "%5%3%")
                    ->setParameter(11, "%5%4%");
                    $module_non_etudierS3S5 = $Qr->getQuery()->getResult();

                    foreach($module_non_etudierS3S5 as $m){
                        $modules_non_etudierS3S5[] = $m->getCode();
                    }
                }

                    // -------------------------------------------------------------------------------------------
                                   // S4 ---------------- S6

                     // ------------- est ce que  prer module 1 valider 2---> OUI

                $Qr = $em->createQueryBuilder();
                $Qr->select('r', 'e')
                ->from('App\Bundle\BackOfficeBundle\Entity\ResultatElp', 'r')
                ->leftJoin('r.element', 'e')
                ->Where($Qr->expr()->like('e.code', '?4'))
                ->orWhere($Qr->expr()->like('e.code', '?5'))
                ->andWhere($Qr->expr()->eq('r.status', '?1'))
                ->andWhere($Qr->expr()->eq('r.etudiant', '?2'))
                ->andWhere($Qr->expr()->eq('e.nature', '?3'))
                ->setParameter(1, "V")
                ->setParameter(2, $etudiant)
                ->setParameter(3, "MOD")
                ->setParameter(4, "%4%1%")
                ->setParameter(5, "%6%1%");  
                $modules_prerequisS4S6M1 = $Qr->getQuery()->getResult();


                 // ----------------- est ce que  prer module 2 valider 2---> OUI
                $Qr = $em->createQueryBuilder();
                $Qr->select('r', 'e')
                ->from('App\Bundle\BackOfficeBundle\Entity\ResultatElp', 'r')
                ->leftJoin('r.element', 'e')
                ->Where($Qr->expr()->like('e.code', '?4'))
                ->orWhere($Qr->expr()->like('e.code', '?5'))
                ->andWhere($Qr->expr()->eq('r.status', '?1'))
                ->andWhere($Qr->expr()->eq('r.etudiant', '?2'))
                ->andWhere($Qr->expr()->eq('e.nature', '?3'))
                ->setParameter(1, "V")
                ->setParameter(2, $etudiant)
                ->setParameter(3, "MOD")
                ->setParameter(4, "%4%2%")
                ->setParameter(5, "%6%2%");  
                $modules_prerequisS4S6M2 = $Qr->getQuery()->getResult();


                 // ------------------- est ce que  prer module 3 valider 2---> OUI
                $Qr = $em->createQueryBuilder();
                $Qr->select('r', 'e')
                ->from('App\Bundle\BackOfficeBundle\Entity\ResultatElp', 'r')
                ->leftJoin('r.element', 'e')
                ->Where($Qr->expr()->like('e.code', '?4'))
                ->orWhere($Qr->expr()->like('e.code', '?5'))
                ->andWhere($Qr->expr()->eq('r.status', '?1'))
                ->andWhere($Qr->expr()->eq('r.etudiant', '?2'))
                ->andWhere($Qr->expr()->eq('e.nature', '?3'))
                ->setParameter(1, "V")
                ->setParameter(2, $etudiant)
                ->setParameter(3, "MOD")
                ->setParameter(4, "%4%3%")
                ->setParameter(5, "%6%3%");  
                $modules_prerequisS4S6M3 = $Qr->getQuery()->getResult();

                 // ---------------------- est ce que  prer module 4 valider 2---> OUI
                $Qr = $em->createQueryBuilder();
                $Qr->select('r', 'e')
                ->from('App\Bundle\BackOfficeBundle\Entity\ResultatElp', 'r')
                ->leftJoin('r.element', 'e')
                ->Where($Qr->expr()->like('e.code', '?4'))
                ->orWhere($Qr->expr()->like('e.code', '?5'))
                ->andWhere($Qr->expr()->eq('r.status', '?1'))
                ->andWhere($Qr->expr()->eq('r.etudiant', '?2'))
                ->andWhere($Qr->expr()->eq('e.nature', '?3'))
                ->setParameter(1, "V")
                ->setParameter(2, $etudiant)
                ->setParameter(3, "MOD")
                ->setParameter(4, "%4%4%")
                ->setParameter(5, "%6%4%");
                $modules_prerequisS4S6M4 = $Qr->getQuery()->getResult();

               $somme = 0; 
               if ( count($modules_prerequisS4S6M1) == 2 )  { 
                   $somme +=  2;          
               }
               if ( count($modules_prerequisS4S6M2) == 2 )  { 
                   $somme +=  2;          
               }
               if ( count($modules_prerequisS4S6M3) == 2 )  { 
                   $somme +=  2;          
               }
               if ( count($modules_prerequisS4S6M4) == 2 )  { 
                   $somme +=  2;          
               }
                  //return new Response(var_dump($somme));

                if($somme == 2 or $somme == 4){
                    // ------------------------------------  Max year 
                    $qb = $em->createQueryBuilder();
                    $qb->select('r')
                    ->addSelect($qb->expr()->max('r.annee'))
                    ->from('App\Bundle\BackOfficeBundle\Entity\ResultatElp', 'r')
                    ->andWhere($qb->expr()->eq('r.etudiant', '?2'))
                    ->setParameter(2, $etudiant);
                    $max = $qb->getQuery()->getResult();
                    $max_yearS4S6 = (int) $max[0][1];
                    //echo $max_year;


                    // ------------------------   Modules non valider pour la derniere annee
                    $Qr = $em->createQueryBuilder();
                    $Qr->select('r', 'e')
                    ->from('App\Bundle\BackOfficeBundle\Entity\ResultatElp', 'r')
                    ->leftJoin('r.element', 'e')
                    ->Where($Qr->expr()->like('e.code', '?4'))
                    ->orWhere($Qr->expr()->like('e.code', '?5'))
                    ->orWhere($Qr->expr()->like('e.code', '?6'))
                    ->orWhere($Qr->expr()->like('e.code', '?7'))
                    ->orWhere($Qr->expr()->like('e.code', '?8'))
                    ->orWhere($Qr->expr()->like('e.code', '?9'))
                    ->orWhere($Qr->expr()->like('e.code', '?10'))
                    ->orWhere($Qr->expr()->like('e.code', '?11'))
                    ->andWhere($Qr->expr()->eq('r.status', '?1'))
                    ->andWhere($Qr->expr()->eq('r.etudiant', '?2'))
                    ->andWhere($Qr->expr()->eq('e.nature', '?3'))
                    ->andWhere($Qr->expr()->eq('r.annee', '?12'))
                    ->setParameter(1, "NV")
                    ->setParameter(2, $etudiant)
                    ->setParameter(3, "MOD")
                    ->setParameter(4, "%4%1%")
                    ->setParameter(5, "%4%2%")
                    ->setParameter(6, "%4%3%")
                    ->setParameter(7, "%4%4%")
                    ->setParameter(8, "%6%1%")
                    ->setParameter(9, "%6%2%")
                    ->setParameter(10, "%6%3%")
                    ->setParameter(11, "%6%4%")
                    ->setParameter(12, $max_yearS4S6);
                    $modules_non_valider_pour_der_anneeS4S6 = $Qr->getQuery()->getResult();

                    foreach($modules_non_valider_pour_der_anneeS4S6 as $m){
                        $module_non_validerS4S6[] = $m->getElement()->getCode();
                    }

                    // ------------- Module valider pour S1S3
                    $Qr = $em->createQueryBuilder();
                    $Qr->select('r', 'e')
                    ->from('App\Bundle\BackOfficeBundle\Entity\ResultatElp', 'r')
                    ->leftJoin('r.element', 'e')
                    ->Where($Qr->expr()->like('e.code', '?4'))
                    ->orWhere($Qr->expr()->like('e.code', '?5'))
                    ->orWhere($Qr->expr()->like('e.code', '?6'))
                    ->orWhere($Qr->expr()->like('e.code', '?7'))
                    ->orWhere($Qr->expr()->like('e.code', '?8'))
                    ->orWhere($Qr->expr()->like('e.code', '?9'))
                    ->orWhere($Qr->expr()->like('e.code', '?10'))
                    ->orWhere($Qr->expr()->like('e.code', '?11'))
                    ->andWhere($Qr->expr()->eq('r.status', '?1'))
                    ->andWhere($Qr->expr()->eq('r.etudiant', '?2'))
                    ->andWhere($Qr->expr()->eq('e.nature', '?3'))
                    ->setParameter(1, "V")
                    ->setParameter(2, $etudiant)
                    ->setParameter(3, "MOD")
                    ->setParameter(4, "%4%1%")
                    ->setParameter(5, "%4%2%")
                    ->setParameter(6, "%4%3%")
                    ->setParameter(7, "%4%4%")
                    ->setParameter(8, "%6%1%")
                    ->setParameter(9, "%6%2%")
                    ->setParameter(10, "%6%3%")
                    ->setParameter(11, "%6%4%");
                    $modules_valider_S4S6 = $Qr->getQuery()->getResult();

                    foreach($modules_valider_S4S6 as $m){
                        $module_validerS4S6[] = $m->getElement()->getCode();
                    }

                    // -------------- Position de prmier 1 
                    $Qr = $em->createQueryBuilder();
                    $Qr->select('r', 'e')
                    ->addSelect("MAX(LOCATE('4', e.code))")
                    ->from('App\Bundle\BackOfficeBundle\Entity\ResultatElp', 'r')
                    ->leftJoin('r.element', 'e')
                    ->Where($Qr->expr()->like('e.code', '?4'))
                    ->orWhere($Qr->expr()->like('e.code', '?5'))
                    ->orWhere($Qr->expr()->like('e.code', '?6'))
                    ->orWhere($Qr->expr()->like('e.code', '?7'))
                    ->orWhere($Qr->expr()->like('e.code', '?8'))
                    ->orWhere($Qr->expr()->like('e.code', '?9'))
                    ->orWhere($Qr->expr()->like('e.code', '?10'))
                    ->orWhere($Qr->expr()->like('e.code', '?11'))
                    ->andWhere($Qr->expr()->eq('r.status', '?1'))
                    ->andWhere($Qr->expr()->eq('r.etudiant', '?2'))
                    ->andWhere($Qr->expr()->eq('e.nature', '?3'))
                    ->setParameter(1, "V")
                    ->setParameter(2, $etudiant)
                    ->setParameter(3, "MOD")
                    ->setParameter(4, "%4%1%")
                    ->setParameter(5, "%4%2%")
                    ->setParameter(6, "%4%3%")
                    ->setParameter(7, "%4%4%")
                    ->setParameter(8, "%6%1%")
                    ->setParameter(9, "%6%2%")
                    ->setParameter(10, "%6%3%")
                    ->setParameter(11, "%6%4%");
                    $positionof4 = $Qr->getQuery()->getResult();
                    $pos4 = (int) $positionof4[0][1];

                     // -------------- Position de prmier 3 
                    $Qr = $em->createQueryBuilder();
                    $Qr->select('r', 'e')
                    ->addSelect("MAX(LOCATE('6', e.code))")
                    ->from('App\Bundle\BackOfficeBundle\Entity\ResultatElp', 'r')
                    ->leftJoin('r.element', 'e')
                    ->Where($Qr->expr()->like('e.code', '?4'))
                    ->orWhere($Qr->expr()->like('e.code', '?5'))
                    ->orWhere($Qr->expr()->like('e.code', '?6'))
                    ->orWhere($Qr->expr()->like('e.code', '?7'))
                    ->orWhere($Qr->expr()->like('e.code', '?8'))
                    ->orWhere($Qr->expr()->like('e.code', '?9'))
                    ->orWhere($Qr->expr()->like('e.code', '?10'))
                    ->orWhere($Qr->expr()->like('e.code', '?11'))
                    ->andWhere($Qr->expr()->eq('r.status', '?1'))
                    ->andWhere($Qr->expr()->eq('r.etudiant', '?2'))
                    ->andWhere($Qr->expr()->eq('e.nature', '?3'))
                    ->setParameter(1, "V")
                    ->setParameter(2, $etudiant)
                    ->setParameter(3, "MOD")
                    ->setParameter(4, "%4%1%")
                    ->setParameter(5, "%4%2%")
                    ->setParameter(6, "%4%3%")
                    ->setParameter(7, "%4%4%")
                    ->setParameter(8, "%6%1%")
                    ->setParameter(9, "%6%2%")
                    ->setParameter(10, "%6%3%")
                    ->setParameter(11, "%6%4%");
                    $positionof6 = $Qr->getQuery()->getResult();
                    $pos6 = (int) $positionof6[0][1];


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

                    //    get filier 
                    $Qr = $em->createQueryBuilder();
                    $Qr->select('r', 'e')
                    ->addSelect("SUBSTRING(e.code, 1, $pos)")
                    ->from('App\Bundle\BackOfficeBundle\Entity\ResultatElp', 'r')
                    ->leftJoin('r.element', 'e')
                    ->Where($Qr->expr()->like('e.code', '?4'))
                    ->orWhere($Qr->expr()->like('e.code', '?5'))
                    ->orWhere($Qr->expr()->like('e.code', '?6'))
                    ->orWhere($Qr->expr()->like('e.code', '?7'))
                    ->orWhere($Qr->expr()->like('e.code', '?8'))
                    ->orWhere($Qr->expr()->like('e.code', '?9'))
                    ->orWhere($Qr->expr()->like('e.code', '?10'))
                    ->orWhere($Qr->expr()->like('e.code', '?11'))
                    ->andWhere($Qr->expr()->eq('r.status', '?1'))
                    ->andWhere($Qr->expr()->eq('r.etudiant', '?2'))
                    ->andWhere($Qr->expr()->eq('e.nature', '?3'))
                    ->setParameter(1, "V")
                    ->setParameter(2, $etudiant)
                    ->setParameter(3, "MOD")
                    ->setParameter(4, "%4%1%")
                    ->setParameter(5, "%4%2%")
                    ->setParameter(6, "%4%3%")
                    ->setParameter(7, "%4%4%")
                    ->setParameter(8, "%6%1%")
                    ->setParameter(9, "%6%2%")
                    ->setParameter(10, "%6%3%")
                    ->setParameter(11, "%6%4%");
                    $FLR = $Qr->getQuery()->getResult();
                    $flr = $FLR[0][1];
                    $filiere = $flr . '%';

                    // MODULE NON Etudier 
                    $Qr = $em->createQueryBuilder();
                    $Qr->select('e')
                    ->from('App\Bundle\BackOfficeBundle\Entity\ElementPedagogi', 'e')
                    ->Where($Qr->expr()->like('e.code', '?4'))
                    ->orWhere($Qr->expr()->like('e.code', '?5'))
                    ->orWhere($Qr->expr()->like('e.code', '?6'))
                    ->orWhere($Qr->expr()->like('e.code', '?7'))
                    ->orWhere($Qr->expr()->like('e.code', '?8'))
                    ->orWhere($Qr->expr()->like('e.code', '?9'))
                    ->orWhere($Qr->expr()->like('e.code', '?10'))
                    ->orWhere($Qr->expr()->like('e.code', '?11'))
                    ->andWhere($Qr->expr()->eq('e.nature', '?3'))
                    ->andWhere($Qr->expr()->like('e.code', '?1'))
                    ->andWhere($Qr->expr()->notIn('e.code', '?12'))
                    ->andWhere($Qr->expr()->notIn('e.code', '?13'))
                    ->setParameter(1, $filiere)
                    ->setParameter(12, $module_non_validerS4S6)
                    ->setParameter(13, $module_validerS4S6)
                    ->setParameter(3, "MOD")
                    ->setParameter(4, "%4%1%")
                    ->setParameter(5, "%4%2%")
                    ->setParameter(6, "%4%3%")
                    ->setParameter(7, "%4%4%")
                    ->setParameter(8, "%6%1%")
                    ->setParameter(9, "%6%2%")
                    ->setParameter(10, "%6%3%")
                    ->setParameter(11, "%6%4%");
                    $module_non_etudierS4S6 = $Qr->getQuery()->getResult();

                    foreach($module_non_etudierS4S6 as $m){
                        $modules_non_etudierS4S6[] = $m->getCode();
                    }
                }
                    //---------------------------------------------------------------------------------------------

                    //  $module_non_valider
                    //  $modules_non_etudier
                    //  $module_valider
                    //return new Response(var_dump($modules_non_etudier));
                    
                    if(count($module_non_validerS3S5) + count($modules_non_etudierS3S5) > 4 | count($module_non_validerS4S6) + count($modules_non_etudierS4S6) > 4){
                        
                        return $this->render('AppFrontOfficeBundle:Changement:changer.html.twig', 
                                              array( 
                                                     "modules_non_validerS1S3" => $modules_non_valider_pour_der_anneeS1S3 ,
                                                     "modules_non_etudierS1S3" => $module_non_etudierS1S3 ,
                                                     "modules_non_validerS2S4" => $modules_non_valider_pour_der_anneeS2S4 ,
                                                     "modules_non_etudierS2S4" => $module_non_etudierS2S4 ,
                                                     "modules_non_validerS3S5" => $modules_non_valider_pour_der_anneeS3S5 ,
                                                     "modules_non_etudierS3S5" => $module_non_etudierS3S5 ,
                                                     "modules_non_validerS4S6" => $modules_non_valider_pour_der_anneeS4S6 ,
                                                     "modules_non_etudierS4S6" => $module_non_etudierS4S6
                                                   ));
                    }
                

                //return $this->render('AppFrontOfficeBundle:Changement:changer.html.twig', array());
             
                 
        // -------------------------------- S1/S3 -----   S2/S4 -----
       
                
                // --------------- Tous les Modules Valider 
                $qb = $em->createQueryBuilder();
                $qb->select('r', 'e')
                ->from('App\Bundle\BackOfficeBundle\Entity\ResultatElp', 'r')
                ->leftJoin('r.element', 'e')
                ->Where($qb->expr()->eq('r.status', '?1'))
                ->andWhere($qb->expr()->eq('r.etudiant', '?2'))
                ->andWhere($qb->expr()->eq('e.nature', '?3'))
                ->setParameter(1, "V")
                ->setParameter(2, $etudiant)
                ->setParameter(3, "MOD");  
                $tous_les_modules_valider = $qb->getQuery()->getResult();


                // ------------- est ce que  prer module 1 valider 2---> OUI

                $Qr = $em->createQueryBuilder();
                $Qr->select('r', 'e')
                ->from('App\Bundle\BackOfficeBundle\Entity\ResultatElp', 'r')
                ->leftJoin('r.element', 'e')
                ->Where($Qr->expr()->like('e.code', '?4'))
                ->orWhere($Qr->expr()->like('e.code', '?5'))
                ->andWhere($Qr->expr()->eq('r.status', '?1'))
                ->andWhere($Qr->expr()->eq('r.etudiant', '?2'))
                ->andWhere($Qr->expr()->eq('e.nature', '?3'))
                ->setParameter(1, "V")
                ->setParameter(2, $etudiant)
                ->setParameter(3, "MOD")
                ->setParameter(4, "%1%1%")
                ->setParameter(5, "%3%1%");  
                $modules_prerequisS1S3M1 = $Qr->getQuery()->getResult();


                 // ----------------- est ce que  prer module 2 valider 2---> OUI
                $Qr = $em->createQueryBuilder();
                $Qr->select('r', 'e')
                ->from('App\Bundle\BackOfficeBundle\Entity\ResultatElp', 'r')
                ->leftJoin('r.element', 'e')
                ->Where($Qr->expr()->like('e.code', '?4'))
                ->orWhere($Qr->expr()->like('e.code', '?5'))
                ->andWhere($Qr->expr()->eq('r.status', '?1'))
                ->andWhere($Qr->expr()->eq('r.etudiant', '?2'))
                ->andWhere($Qr->expr()->eq('e.nature', '?3'))
                ->setParameter(1, "V")
                ->setParameter(2, $etudiant)
                ->setParameter(3, "MOD")
                ->setParameter(4, "%1%2%")
                ->setParameter(5, "%3%2%");  
                $modules_prerequisS1S3M2 = $Qr->getQuery()->getResult();


                 // ------------------- est ce que  prer module 3 valider 2---> OUI
                $Qr = $em->createQueryBuilder();
                $Qr->select('r', 'e')
                ->from('App\Bundle\BackOfficeBundle\Entity\ResultatElp', 'r')
                ->leftJoin('r.element', 'e')
                ->Where($Qr->expr()->like('e.code', '?4'))
                ->orWhere($Qr->expr()->like('e.code', '?5'))
                ->andWhere($Qr->expr()->eq('r.status', '?1'))
                ->andWhere($Qr->expr()->eq('r.etudiant', '?2'))
                ->andWhere($Qr->expr()->eq('e.nature', '?3'))
                ->setParameter(1, "V")
                ->setParameter(2, $etudiant)
                ->setParameter(3, "MOD")
                ->setParameter(4, "%1%3%")
                ->setParameter(5, "%3%3%");  
                $modules_prerequisS1S3M3 = $Qr->getQuery()->getResult();

                 // ---------------------- est ce que  prer module 4 valider 2---> OUI
                $Qr = $em->createQueryBuilder();
                $Qr->select('r', 'e')
                ->from('App\Bundle\BackOfficeBundle\Entity\ResultatElp', 'r')
                ->leftJoin('r.element', 'e')
                ->Where($Qr->expr()->like('e.code', '?4'))
                ->orWhere($Qr->expr()->like('e.code', '?5'))
                ->andWhere($Qr->expr()->eq('r.status', '?1'))
                ->andWhere($Qr->expr()->eq('r.etudiant', '?2'))
                ->andWhere($Qr->expr()->eq('e.nature', '?3'))
                ->setParameter(1, "V")
                ->setParameter(2, $etudiant)
                ->setParameter(3, "MOD")
                ->setParameter(4, "%1%4%")
                ->setParameter(5, "%3%4%");
                $modules_prerequisS1S3M4 = $Qr->getQuery()->getResult();

               $somme = 0; 
               if ( count($modules_prerequisS1S3M1) == 2 )  { 
                   $somme +=  2;          
               }
               if ( count($modules_prerequisS1S3M2) == 2 )  { 
                   $somme +=  2;          
               }
               if ( count($modules_prerequisS1S3M3) == 2 )  { 
                   $somme +=  2;          
               }
               if ( count($modules_prerequisS1S3M4) == 2 )  { 
                   $somme +=  2;          
               }
                  //return new Response(var_dump($somme));

                if($somme == 2 or $somme == 4){
                    // ------------------------------------  Max year 
                    $qb = $em->createQueryBuilder();
                    $qb->select('r')
                    ->addSelect($qb->expr()->max('r.annee'))
                    ->from('App\Bundle\BackOfficeBundle\Entity\ResultatElp', 'r')
                    ->andWhere($qb->expr()->eq('r.etudiant', '?2'))
                    ->setParameter(2, $etudiant);
                    $max = $qb->getQuery()->getResult();
                    $max_yearS1S3 = (int) $max[0][1];
                    //echo $max_year;


                    // ------------------------   Modules non valider pour la derniere annee
                    $Qr = $em->createQueryBuilder();
                    $Qr->select('r', 'e')
                    ->from('App\Bundle\BackOfficeBundle\Entity\ResultatElp', 'r')
                    ->leftJoin('r.element', 'e')
                    ->Where($Qr->expr()->like('e.code', '?4'))
                    ->orWhere($Qr->expr()->like('e.code', '?5'))
                    ->orWhere($Qr->expr()->like('e.code', '?6'))
                    ->orWhere($Qr->expr()->like('e.code', '?7'))
                    ->orWhere($Qr->expr()->like('e.code', '?8'))
                    ->orWhere($Qr->expr()->like('e.code', '?9'))
                    ->orWhere($Qr->expr()->like('e.code', '?10'))
                    ->orWhere($Qr->expr()->like('e.code', '?11'))
                    ->andWhere($Qr->expr()->eq('r.status', '?1'))
                    ->andWhere($Qr->expr()->eq('r.etudiant', '?2'))
                    ->andWhere($Qr->expr()->eq('e.nature', '?3'))
                    ->andWhere($Qr->expr()->eq('r.annee', '?12'))
                    ->setParameter(1, "NV")
                    ->setParameter(2, $etudiant)
                    ->setParameter(3, "MOD")
                    ->setParameter(4, "%1%1%")
                    ->setParameter(5, "%1%2%")
                    ->setParameter(6, "%1%3%")
                    ->setParameter(7, "%1%4%")
                    ->setParameter(8, "%3%1%")
                    ->setParameter(9, "%3%2%")
                    ->setParameter(10, "%3%3%")
                    ->setParameter(11, "%3%4%")
                    ->setParameter(12, $max_yearS1S3);
                    $modules_non_valider_pour_der_anneeS1S3 = $Qr->getQuery()->getResult();

                    foreach($modules_non_valider_pour_der_anneeS1S3 as $m){
                        $module_non_validerS1S3[] = $m->getElement()->getCode();
                    }

                    // ------------- Module valider pour S1S3
                    $Qr = $em->createQueryBuilder();
                    $Qr->select('r', 'e')
                    ->from('App\Bundle\BackOfficeBundle\Entity\ResultatElp', 'r')
                    ->leftJoin('r.element', 'e')
                    ->Where($Qr->expr()->like('e.code', '?4'))
                    ->orWhere($Qr->expr()->like('e.code', '?5'))
                    ->orWhere($Qr->expr()->like('e.code', '?6'))
                    ->orWhere($Qr->expr()->like('e.code', '?7'))
                    ->orWhere($Qr->expr()->like('e.code', '?8'))
                    ->orWhere($Qr->expr()->like('e.code', '?9'))
                    ->orWhere($Qr->expr()->like('e.code', '?10'))
                    ->orWhere($Qr->expr()->like('e.code', '?11'))
                    ->andWhere($Qr->expr()->eq('r.status', '?1'))
                    ->andWhere($Qr->expr()->eq('r.etudiant', '?2'))
                    ->andWhere($Qr->expr()->eq('e.nature', '?3'))
                    ->setParameter(1, "V")
                    ->setParameter(2, $etudiant)
                    ->setParameter(3, "MOD")
                    ->setParameter(4, "%1%1%")
                    ->setParameter(5, "%1%2%")
                    ->setParameter(6, "%1%3%")
                    ->setParameter(7, "%1%4%")
                    ->setParameter(8, "%3%1%")
                    ->setParameter(9, "%3%2%")
                    ->setParameter(10, "%3%3%")
                    ->setParameter(11, "%3%4%");
                    $modules_valider_S1S3 = $Qr->getQuery()->getResult();

                    foreach($modules_valider_S1S3 as $m){
                        $module_validerS1S3[] = $m->getElement()->getCode();
                    }

                    // -------------- Position de prmier 1 
                    $Qr = $em->createQueryBuilder();
                    $Qr->select('r', 'e')
                    ->addSelect("MAX(LOCATE('1', e.code))")
                    ->from('App\Bundle\BackOfficeBundle\Entity\ResultatElp', 'r')
                    ->leftJoin('r.element', 'e')
                    ->Where($Qr->expr()->like('e.code', '?4'))
                    ->orWhere($Qr->expr()->like('e.code', '?5'))
                    ->orWhere($Qr->expr()->like('e.code', '?6'))
                    ->orWhere($Qr->expr()->like('e.code', '?7'))
                    ->orWhere($Qr->expr()->like('e.code', '?8'))
                    ->orWhere($Qr->expr()->like('e.code', '?9'))
                    ->orWhere($Qr->expr()->like('e.code', '?10'))
                    ->orWhere($Qr->expr()->like('e.code', '?11'))
                    ->andWhere($Qr->expr()->eq('r.status', '?1'))
                    ->andWhere($Qr->expr()->eq('r.etudiant', '?2'))
                    ->andWhere($Qr->expr()->eq('e.nature', '?3'))
                    ->setParameter(1, "V")
                    ->setParameter(2, $etudiant)
                    ->setParameter(3, "MOD")
                    ->setParameter(4, "%1%1%")
                    ->setParameter(5, "%1%2%")
                    ->setParameter(6, "%1%3%")
                    ->setParameter(7, "%1%4%")
                    ->setParameter(8, "%3%1%")
                    ->setParameter(9, "%3%2%")
                    ->setParameter(10, "%3%3%")
                    ->setParameter(11, "%3%4%");
                    $positionof1 = $Qr->getQuery()->getResult();
                    $pos1 = (int) $positionof1[0][1];

                     // -------------- Position de prmier 3 
                    $Qr = $em->createQueryBuilder();
                    $Qr->select('r', 'e')
                    ->addSelect("MAX(LOCATE('3', e.code))")
                    ->from('App\Bundle\BackOfficeBundle\Entity\ResultatElp', 'r')
                    ->leftJoin('r.element', 'e')
                    ->Where($Qr->expr()->like('e.code', '?4'))
                    ->orWhere($Qr->expr()->like('e.code', '?5'))
                    ->orWhere($Qr->expr()->like('e.code', '?6'))
                    ->orWhere($Qr->expr()->like('e.code', '?7'))
                    ->orWhere($Qr->expr()->like('e.code', '?8'))
                    ->orWhere($Qr->expr()->like('e.code', '?9'))
                    ->orWhere($Qr->expr()->like('e.code', '?10'))
                    ->orWhere($Qr->expr()->like('e.code', '?11'))
                    ->andWhere($Qr->expr()->eq('r.status', '?1'))
                    ->andWhere($Qr->expr()->eq('r.etudiant', '?2'))
                    ->andWhere($Qr->expr()->eq('e.nature', '?3'))
                    ->setParameter(1, "V")
                    ->setParameter(2, $etudiant)
                    ->setParameter(3, "MOD")
                    ->setParameter(4, "%1%1%")
                    ->setParameter(5, "%1%2%")
                    ->setParameter(6, "%1%3%")
                    ->setParameter(7, "%1%4%")
                    ->setParameter(8, "%3%1%")
                    ->setParameter(9, "%3%2%")
                    ->setParameter(10, "%3%3%")
                    ->setParameter(11, "%3%4%");
                    $positionof3 = $Qr->getQuery()->getResult();
                    $pos3 = (int) $positionof3[0][1];


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

                    //    get filier 
                    $Qr = $em->createQueryBuilder();
                    $Qr->select('r', 'e')
                    ->addSelect("SUBSTRING(e.code, 1, $pos)")
                    ->from('App\Bundle\BackOfficeBundle\Entity\ResultatElp', 'r')
                    ->leftJoin('r.element', 'e')
                    ->Where($Qr->expr()->like('e.code', '?4'))
                    ->orWhere($Qr->expr()->like('e.code', '?5'))
                    ->orWhere($Qr->expr()->like('e.code', '?6'))
                    ->orWhere($Qr->expr()->like('e.code', '?7'))
                    ->orWhere($Qr->expr()->like('e.code', '?8'))
                    ->orWhere($Qr->expr()->like('e.code', '?9'))
                    ->orWhere($Qr->expr()->like('e.code', '?10'))
                    ->orWhere($Qr->expr()->like('e.code', '?11'))
                    ->andWhere($Qr->expr()->eq('r.status', '?1'))
                    ->andWhere($Qr->expr()->eq('r.etudiant', '?2'))
                    ->andWhere($Qr->expr()->eq('e.nature', '?3'))
                    ->setParameter(1, "V")
                    ->setParameter(2, $etudiant)
                    ->setParameter(3, "MOD")
                    ->setParameter(4, "%1%1%")
                    ->setParameter(5, "%1%2%")
                    ->setParameter(6, "%1%3%")
                    ->setParameter(7, "%1%4%")
                    ->setParameter(8, "%3%1%")
                    ->setParameter(9, "%3%2%")
                    ->setParameter(10, "%3%3%")
                    ->setParameter(11, "%3%4%");
                    $FLR = $Qr->getQuery()->getResult();
                    $flr = $FLR[0][1];
                    $filiere = $flr . '%';

                    // MODULE NON Etudier 
                    $Qr = $em->createQueryBuilder();
                    $Qr->select('e')
                    ->from('App\Bundle\BackOfficeBundle\Entity\ElementPedagogi', 'e')
                    ->Where($Qr->expr()->like('e.code', '?4'))
                    ->orWhere($Qr->expr()->like('e.code', '?5'))
                    ->orWhere($Qr->expr()->like('e.code', '?6'))
                    ->orWhere($Qr->expr()->like('e.code', '?7'))
                    ->orWhere($Qr->expr()->like('e.code', '?8'))
                    ->orWhere($Qr->expr()->like('e.code', '?9'))
                    ->orWhere($Qr->expr()->like('e.code', '?10'))
                    ->orWhere($Qr->expr()->like('e.code', '?11'))
                    ->andWhere($Qr->expr()->eq('e.nature', '?3'))
                    ->andWhere($Qr->expr()->like('e.code', '?1'))
                    ->andWhere($Qr->expr()->notIn('e.code', '?12'))
                    ->andWhere($Qr->expr()->notIn('e.code', '?13'))
                    ->setParameter(1, $filiere)
                    ->setParameter(12, $module_non_validerS1S3)
                    ->setParameter(13, $module_validerS1S3)
                    ->setParameter(3, "MOD")
                    ->setParameter(4, "%1%1%")
                    ->setParameter(5, "%1%2%")
                    ->setParameter(6, "%1%3%")
                    ->setParameter(7, "%1%4%")
                    ->setParameter(8, "%3%1%")
                    ->setParameter(9, "%3%2%")
                    ->setParameter(10, "%3%3%")
                    ->setParameter(11, "%3%4%");
                    $module_non_etudierS1S3 = $Qr->getQuery()->getResult();

                    foreach($module_non_etudierS1S3 as $m){
                        $modules_non_etudierS1S3[] = $m->getCode();
                    }
                }


                    // -------------------------------------------------------------------------------------------
                                   // S2 ---------------- S4

                     // ------------- est ce que  prer module 1 valider 2---> OUI

                $Qr = $em->createQueryBuilder();
                $Qr->select('r', 'e')
                ->from('App\Bundle\BackOfficeBundle\Entity\ResultatElp', 'r')
                ->leftJoin('r.element', 'e')
                ->Where($Qr->expr()->like('e.code', '?4'))
                ->orWhere($Qr->expr()->like('e.code', '?5'))
                ->andWhere($Qr->expr()->eq('r.status', '?1'))
                ->andWhere($Qr->expr()->eq('r.etudiant', '?2'))
                ->andWhere($Qr->expr()->eq('e.nature', '?3'))
                ->setParameter(1, "V")
                ->setParameter(2, $etudiant)
                ->setParameter(3, "MOD")
                ->setParameter(4, "%2%1%")
                ->setParameter(5, "%4%1%");  
                $modules_prerequisS2S4M1 = $Qr->getQuery()->getResult();


                 // ----------------- est ce que  prer module 2 valider 2---> OUI
                $Qr = $em->createQueryBuilder();
                $Qr->select('r', 'e')
                ->from('App\Bundle\BackOfficeBundle\Entity\ResultatElp', 'r')
                ->leftJoin('r.element', 'e')
                ->Where($Qr->expr()->like('e.code', '?4'))
                ->orWhere($Qr->expr()->like('e.code', '?5'))
                ->andWhere($Qr->expr()->eq('r.status', '?1'))
                ->andWhere($Qr->expr()->eq('r.etudiant', '?2'))
                ->andWhere($Qr->expr()->eq('e.nature', '?3'))
                ->setParameter(1, "V")
                ->setParameter(2, $etudiant)
                ->setParameter(3, "MOD")
                ->setParameter(4, "%2%2%")
                ->setParameter(5, "%4%2%");  
                $modules_prerequisS2S4M2 = $Qr->getQuery()->getResult();


                 // ------------------- est ce que  prer module 3 valider 2---> OUI
                $Qr = $em->createQueryBuilder();
                $Qr->select('r', 'e')
                ->from('App\Bundle\BackOfficeBundle\Entity\ResultatElp', 'r')
                ->leftJoin('r.element', 'e')
                ->Where($Qr->expr()->like('e.code', '?4'))
                ->orWhere($Qr->expr()->like('e.code', '?5'))
                ->andWhere($Qr->expr()->eq('r.status', '?1'))
                ->andWhere($Qr->expr()->eq('r.etudiant', '?2'))
                ->andWhere($Qr->expr()->eq('e.nature', '?3'))
                ->setParameter(1, "V")
                ->setParameter(2, $etudiant)
                ->setParameter(3, "MOD")
                ->setParameter(4, "%2%3%")
                ->setParameter(5, "%4%3%");  
                $modules_prerequisS2S4M3 = $Qr->getQuery()->getResult();

                 // ---------------------- est ce que  prer module 4 valider 2---> OUI
                $Qr = $em->createQueryBuilder();
                $Qr->select('r', 'e')
                ->from('App\Bundle\BackOfficeBundle\Entity\ResultatElp', 'r')
                ->leftJoin('r.element', 'e')
                ->Where($Qr->expr()->like('e.code', '?4'))
                ->orWhere($Qr->expr()->like('e.code', '?5'))
                ->andWhere($Qr->expr()->eq('r.status', '?1'))
                ->andWhere($Qr->expr()->eq('r.etudiant', '?2'))
                ->andWhere($Qr->expr()->eq('e.nature', '?3'))
                ->setParameter(1, "V")
                ->setParameter(2, $etudiant)
                ->setParameter(3, "MOD")
                ->setParameter(4, "%2%4%")
                ->setParameter(5, "%4%4%");
                $modules_prerequisS2S4M4 = $Qr->getQuery()->getResult();

               $somme = 0; 
               if ( count($modules_prerequisS2S4M1) == 2 )  { 
                   $somme +=  2;          
               }
               if ( count($modules_prerequisS2S4M2) == 2 )  { 
                   $somme +=  2;          
               }
               if ( count($modules_prerequisS2S4M3) == 2 )  { 
                   $somme +=  2;          
               }
               if ( count($modules_prerequisS2S4M4) == 2 )  { 
                   $somme +=  2;          
               }
                  //return new Response(var_dump($somme));

                if($somme == 2 or $somme == 4){
                    // ------------------------------------  Max year 
                    $qb = $em->createQueryBuilder();
                    $qb->select('r')
                    ->addSelect($qb->expr()->max('r.annee'))
                    ->from('App\Bundle\BackOfficeBundle\Entity\ResultatElp', 'r')
                    ->andWhere($qb->expr()->eq('r.etudiant', '?2'))
                    ->setParameter(2, $etudiant);
                    $max = $qb->getQuery()->getResult();
                    $max_yearS2S4 = (int) $max[0][1];
                    //echo $max_year;


                    // ------------------------   Modules non valider pour la derniere annee
                    $Qr = $em->createQueryBuilder();
                    $Qr->select('r', 'e')
                    ->from('App\Bundle\BackOfficeBundle\Entity\ResultatElp', 'r')
                    ->leftJoin('r.element', 'e')
                    ->Where($Qr->expr()->like('e.code', '?4'))
                    ->orWhere($Qr->expr()->like('e.code', '?5'))
                    ->orWhere($Qr->expr()->like('e.code', '?6'))
                    ->orWhere($Qr->expr()->like('e.code', '?7'))
                    ->orWhere($Qr->expr()->like('e.code', '?8'))
                    ->orWhere($Qr->expr()->like('e.code', '?9'))
                    ->orWhere($Qr->expr()->like('e.code', '?10'))
                    ->orWhere($Qr->expr()->like('e.code', '?11'))
                    ->andWhere($Qr->expr()->eq('r.status', '?1'))
                    ->andWhere($Qr->expr()->eq('r.etudiant', '?2'))
                    ->andWhere($Qr->expr()->eq('e.nature', '?3'))
                    ->andWhere($Qr->expr()->eq('r.annee', '?12'))
                    ->setParameter(1, "NV")
                    ->setParameter(2, $etudiant)
                    ->setParameter(3, "MOD")
                    ->setParameter(4, "%2%1%")
                    ->setParameter(5, "%2%2%")
                    ->setParameter(6, "%2%3%")
                    ->setParameter(7, "%2%4%")
                    ->setParameter(8, "%4%1%")
                    ->setParameter(9, "%4%2%")
                    ->setParameter(10, "%4%3%")
                    ->setParameter(11, "%4%4%")
                    ->setParameter(12, $max_yearS2S4);
                    $modules_non_valider_pour_der_anneeS2S4 = $Qr->getQuery()->getResult();

                    foreach($modules_non_valider_pour_der_anneeS2S4 as $m){
                        $module_non_validerS2S4[] = $m->getElement()->getCode();
                    }

                    // ------------- Module valider pour S1S3
                    $Qr = $em->createQueryBuilder();
                    $Qr->select('r', 'e')
                    ->from('App\Bundle\BackOfficeBundle\Entity\ResultatElp', 'r')
                    ->leftJoin('r.element', 'e')
                    ->Where($Qr->expr()->like('e.code', '?4'))
                    ->orWhere($Qr->expr()->like('e.code', '?5'))
                    ->orWhere($Qr->expr()->like('e.code', '?6'))
                    ->orWhere($Qr->expr()->like('e.code', '?7'))
                    ->orWhere($Qr->expr()->like('e.code', '?8'))
                    ->orWhere($Qr->expr()->like('e.code', '?9'))
                    ->orWhere($Qr->expr()->like('e.code', '?10'))
                    ->orWhere($Qr->expr()->like('e.code', '?11'))
                    ->andWhere($Qr->expr()->eq('r.status', '?1'))
                    ->andWhere($Qr->expr()->eq('r.etudiant', '?2'))
                    ->andWhere($Qr->expr()->eq('e.nature', '?3'))
                    ->setParameter(1, "V")
                    ->setParameter(2, $etudiant)
                    ->setParameter(3, "MOD")
                    ->setParameter(4, "%2%1%")
                    ->setParameter(5, "%2%2%")
                    ->setParameter(6, "%2%3%")
                    ->setParameter(7, "%2%4%")
                    ->setParameter(8, "%4%1%")
                    ->setParameter(9, "%4%2%")
                    ->setParameter(10, "%4%3%")
                    ->setParameter(11, "%4%4%");
                    $modules_valider_S2S4 = $Qr->getQuery()->getResult();

                    foreach($modules_valider_S2S4 as $m){
                        $module_validerS2S4[] = $m->getElement()->getCode();
                    }

                    // -------------- Position de prmier 1 
                    $Qr = $em->createQueryBuilder();
                    $Qr->select('r', 'e')
                    ->addSelect("MAX(LOCATE('2', e.code))")
                    ->from('App\Bundle\BackOfficeBundle\Entity\ResultatElp', 'r')
                    ->leftJoin('r.element', 'e')
                    ->Where($Qr->expr()->like('e.code', '?4'))
                    ->orWhere($Qr->expr()->like('e.code', '?5'))
                    ->orWhere($Qr->expr()->like('e.code', '?6'))
                    ->orWhere($Qr->expr()->like('e.code', '?7'))
                    ->orWhere($Qr->expr()->like('e.code', '?8'))
                    ->orWhere($Qr->expr()->like('e.code', '?9'))
                    ->orWhere($Qr->expr()->like('e.code', '?10'))
                    ->orWhere($Qr->expr()->like('e.code', '?11'))
                    ->andWhere($Qr->expr()->eq('r.status', '?1'))
                    ->andWhere($Qr->expr()->eq('r.etudiant', '?2'))
                    ->andWhere($Qr->expr()->eq('e.nature', '?3'))
                    ->setParameter(1, "V")
                    ->setParameter(2, $etudiant)
                    ->setParameter(3, "MOD")
                    ->setParameter(4, "%2%1%")
                    ->setParameter(5, "%2%2%")
                    ->setParameter(6, "%2%3%")
                    ->setParameter(7, "%2%4%")
                    ->setParameter(8, "%4%1%")
                    ->setParameter(9, "%4%2%")
                    ->setParameter(10, "%4%3%")
                    ->setParameter(11, "%4%4%");
                    $positionof2 = $Qr->getQuery()->getResult();
                    $pos2 = (int) $positionof2[0][1];

                     // -------------- Position de prmier 3 
                    $Qr = $em->createQueryBuilder();
                    $Qr->select('r', 'e')
                    ->addSelect("MAX(LOCATE('4', e.code))")
                    ->from('App\Bundle\BackOfficeBundle\Entity\ResultatElp', 'r')
                    ->leftJoin('r.element', 'e')
                    ->Where($Qr->expr()->like('e.code', '?4'))
                    ->orWhere($Qr->expr()->like('e.code', '?5'))
                    ->orWhere($Qr->expr()->like('e.code', '?6'))
                    ->orWhere($Qr->expr()->like('e.code', '?7'))
                    ->orWhere($Qr->expr()->like('e.code', '?8'))
                    ->orWhere($Qr->expr()->like('e.code', '?9'))
                    ->orWhere($Qr->expr()->like('e.code', '?10'))
                    ->orWhere($Qr->expr()->like('e.code', '?11'))
                    ->andWhere($Qr->expr()->eq('r.status', '?1'))
                    ->andWhere($Qr->expr()->eq('r.etudiant', '?2'))
                    ->andWhere($Qr->expr()->eq('e.nature', '?3'))
                    ->setParameter(1, "V")
                    ->setParameter(2, $etudiant)
                    ->setParameter(3, "MOD")
                    ->setParameter(4, "%2%1%")
                    ->setParameter(5, "%2%2%")
                    ->setParameter(6, "%2%3%")
                    ->setParameter(7, "%2%4%")
                    ->setParameter(8, "%4%1%")
                    ->setParameter(9, "%4%2%")
                    ->setParameter(10, "%4%3%")
                    ->setParameter(11, "%4%4%");
                    $positionof4 = $Qr->getQuery()->getResult();
                    $pos4 = (int) $positionof4[0][1];


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

                    //    get filier 
                    $Qr = $em->createQueryBuilder();
                    $Qr->select('r', 'e')
                    ->addSelect("SUBSTRING(e.code, 1, $pos)")
                    ->from('App\Bundle\BackOfficeBundle\Entity\ResultatElp', 'r')
                    ->leftJoin('r.element', 'e')
                    ->Where($Qr->expr()->like('e.code', '?4'))
                    ->orWhere($Qr->expr()->like('e.code', '?5'))
                    ->orWhere($Qr->expr()->like('e.code', '?6'))
                    ->orWhere($Qr->expr()->like('e.code', '?7'))
                    ->orWhere($Qr->expr()->like('e.code', '?8'))
                    ->orWhere($Qr->expr()->like('e.code', '?9'))
                    ->orWhere($Qr->expr()->like('e.code', '?10'))
                    ->orWhere($Qr->expr()->like('e.code', '?11'))
                    ->andWhere($Qr->expr()->eq('r.status', '?1'))
                    ->andWhere($Qr->expr()->eq('r.etudiant', '?2'))
                    ->andWhere($Qr->expr()->eq('e.nature', '?3'))
                    ->setParameter(1, "V")
                    ->setParameter(2, $etudiant)
                    ->setParameter(3, "MOD")
                    ->setParameter(4, "%2%1%")
                    ->setParameter(5, "%2%2%")
                    ->setParameter(6, "%2%3%")
                    ->setParameter(7, "%2%4%")
                    ->setParameter(8, "%4%1%")
                    ->setParameter(9, "%4%2%")
                    ->setParameter(10, "%4%3%")
                    ->setParameter(11, "%4%4%");
                    $FLR = $Qr->getQuery()->getResult();
                    $flr = $FLR[0][1];
                    $filiere = $flr . '%';

                    // MODULE NON Etudier 
                    $Qr = $em->createQueryBuilder();
                    $Qr->select('e')
                    ->from('App\Bundle\BackOfficeBundle\Entity\ElementPedagogi', 'e')
                    ->Where($Qr->expr()->like('e.code', '?4'))
                    ->orWhere($Qr->expr()->like('e.code', '?5'))
                    ->orWhere($Qr->expr()->like('e.code', '?6'))
                    ->orWhere($Qr->expr()->like('e.code', '?7'))
                    ->orWhere($Qr->expr()->like('e.code', '?8'))
                    ->orWhere($Qr->expr()->like('e.code', '?9'))
                    ->orWhere($Qr->expr()->like('e.code', '?10'))
                    ->orWhere($Qr->expr()->like('e.code', '?11'))
                    ->andWhere($Qr->expr()->eq('e.nature', '?3'))
                    ->andWhere($Qr->expr()->like('e.code', '?1'))
                    ->andWhere($Qr->expr()->notIn('e.code', '?12'))
                    ->andWhere($Qr->expr()->notIn('e.code', '?13'))
                    ->setParameter(1, $filiere)
                    ->setParameter(12, $module_non_validerS2S4)
                    ->setParameter(13, $module_validerS2S4)
                    ->setParameter(3, "MOD")
                    ->setParameter(4, "%2%1%")
                    ->setParameter(5, "%2%2%")
                    ->setParameter(6, "%2%3%")
                    ->setParameter(7, "%2%4%")
                    ->setParameter(8, "%4%1%")
                    ->setParameter(9, "%4%2%")
                    ->setParameter(10, "%4%3%")
                    ->setParameter(11, "%4%4%");
                    $module_non_etudierS2S4 = $Qr->getQuery()->getResult();

                    foreach($module_non_etudierS2S4 as $m){
                        $modules_non_etudierS2S4[] = $m->getCode();
                    }
                }
                    //---------------------------------------------------------------------------------------------

                    //  $module_non_valider
                    //  $modules_non_etudier
                    //  $module_valider
                    //return new Response(var_dump($modules_non_etudier));
                    if(count($module_non_validerS1S3) + count($modules_non_etudierS1S3) > 4 | count($module_non_validerS2S4) + count($modules_non_etudierS2S4) > 4){
                        return $this->render('AppFrontOfficeBundle:Changement:changer.html.twig', 
                                              array( 
                                                     "modules_non_validerS1S3" => $modules_non_valider_pour_der_anneeS1S3 ,
                                                     "modules_non_etudierS1S3" => $module_non_etudierS1S3 ,
                                                     "modules_non_validerS2S4" => $modules_non_valider_pour_der_anneeS2S4 ,
                                                     "modules_non_etudierS2S4" => $module_non_etudierS2S4 ,
                                                     "modules_non_validerS3S5" => $modules_non_valider_pour_der_anneeS3S5 ,
                                                     "modules_non_etudierS3S5" => $module_non_etudierS3S5 ,
                                                     "modules_non_validerS4S6" => $modules_non_valider_pour_der_anneeS4S6 ,
                                                     "modules_non_etudierS4S6" => $module_non_etudierS4S6
                                                   ));
                    }
                

                
             
             
            return $this->render('AppFrontOfficeBundle:Changement:pasdemoduleachanger.html.twig', array());
    }
}
