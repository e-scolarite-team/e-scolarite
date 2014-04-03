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
/*use App\Bundle\BackOfficeBundle;*/



class DemandeController extends Controller {
  


   public function demandepieceAction() {

        $em = $this->getDoctrine()->getEntityManager();
        
            if($this->get('request')->request->get('demande') != ""){           

                    $repTypeDemande = $this->getDoctrine()->getRepository('AppBackOfficeBundle:TypeDemande');
                    $typedemande = $repTypeDemande->findOneByCode($this->get('request')->request->get('demande')); 

                      $etudiant = $this->getUser();

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
                                        'AppFrontOfficeBundle:Demande:countdepasse.html.twig', 
                                        array( 'count' => $typedemande->getMaxAutorise(),  'demande' => $demande )
                        );
                    }

                    $demande->setStatus(0);
                    $demande->setNotified(0);
                    //--------------------------------------------
                    $this->setDateReponceOfDemande($demande);
                    
                    //--------------------------------------------
                    $em->persist($demande);
                    $etatDemandes = new EtatDemande();
                    $etatDemandes->setEtat("en attente");
                    $etatDemandes->setDemande($demande);
                    $em->persist($etatDemandes);
                    $em->flush();

                     return $this->render(
                                        'AppFrontOfficeBundle:Demande:demandeautorise.html.twig', 
                                         array(  'demande' => $demande )
                                    );
            }       
        
            $qb = $em->createQueryBuilder();
            $qb->select('t')
            ->from('App\Bundle\BackOfficeBundle\Entity\TypeDemande', 't')
            ->where($qb->expr()->notIn('t.code', '?1'))
            ->setParameter(1, array('ER','5M','CM'));

            $typesdemandes = $qb->getQuery()->getResult();
            return $this->render(
                                'AppFrontOfficeBundle:Demande:demandepiece.html.twig', 
                                array( 'typesdemandes' => $typesdemandes )
                            );
    }

public function setDateReponceOfDemande($demande)
{     
   if(true){
        $lastDate=$this->getDoctrine()->getEntityManager()->getRepository("AppBackOfficeBundle:Demande")->getLastReponceDate();
        $date=$this->getAppropriateDate($lastDate);
        $demande->setDateReponce($date);
        $demande->setNotified(1);
        $demande->setStatus(2);
                    }

}
   
 private function getAppropriateDate($date)
   {
    $appropriateDate=\date_create($date);
    $test=$this->isFull($date);
    if($test){
        return (\date("D",strtotime($date))=='Fri')?\date_add($appropriateDate, date_interval_create_from_date_string('3 days')):\date_add($appropriateDate, date_interval_create_from_date_string('1 days'));
    }
    return $appropriateDate;
  }
  private function isFull($date)
  {
      $day=date("D",strtotime($date));  
      $amountAnswers=($day=='Fri')?$this->container->get("esconfig_manager")->getAutoAnswersAmount()*3:$this->container->get("esconfig_manager")->getAutoAnswersAmount();
      $demandesCount=count($this->getDoctrine()->getEntityManager()->getRepository("AppBackOfficeBundle:Demande")->findByDateReponce(\date_create($date)));
        
      if($demandesCount>=$amountAnswers){
        return true;
      }
      return false;
      //$this->container->get("esconfig_manager")->getAutoAnswersAmount();
  }

    // -------------------------------------------------------------------
    

   public function rendezvousAction() {
        
        $em = $this->getDoctrine()->getEntityManager();
        
      	$etudiant = $this->getUser();

        
        $td = $em->createQueryBuilder();
        $td->select('t')
        ->from('App\Bundle\BackOfficeBundle\Entity\TypeDemande', 't')
        ->Where($td->expr()->in('t.code', '?1'))
        ->setParameter(1, array( 'ER', '5M', 'CM'));
        $typedemandes = $td->getQuery()->getResult();
        //return new Response(var_dump($typedemandes));
        
        $qb = $em->createQueryBuilder();
        $qb->select('d')
        ->from('App\Bundle\BackOfficeBundle\Entity\Demande', 'd')
        ->Where($qb->expr()->eq('d.etudiant', '?1'))
        ->andWhere($qb->expr()->notIn('d.typeDemande', '?2'))
        ->setParameter(1, $etudiant)
        ->setParameter(2, $typedemandes);
        $demandes = $qb->getQuery()->getResult();
        
        $qb = $em->createQueryBuilder();
        $qb->select('e')
        ->from('App\Bundle\BackOfficeBundle\Entity\EtatDemande', 'e')
        ->where($qb->expr()->in('e.demande', '?1'))
        ->setParameter(1, $demandes);
        $etatdemandes = $qb->getQuery()->getResult();
            
        return $this->render(
                                'AppFrontOfficeBundle:Demande:rendezvous.html.twig', 
                                array( 'etatdemandes' => $etatdemandes )
                            );
    }

  /**
     * Finds and displays a TypeDemande entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();
       
        $entity = $em->getRepository('AppBackOfficeBundle:Demande')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Demande entity.');
        }

        //$deleteForm = $this->createDeleteForm($id);
        
            
        return $this->render('AppFrontOfficeBundle:Demande:show.html.twig', array(
            'entity'      => $entity,
            //'delete_form' => $deleteForm->createView(), 
            
                   ));
    }
    

}

