<?php

namespace App\Bundle\BackOfficeBundle\Controller;

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


class DemandeController extends Controller {

    // --------------------------------------------------------------------

public function getAutoDateReponce()
{     
        $lastDate=$this->getDoctrine()->getEntityManager()->getRepository("AppBackOfficeBundle:Demande")->getLastReponceDate();
        $dateInterval=(\date_create($lastDate)->diff(new \DateTime('now')));
        $dateIntervalStr=$dateInterval->format('%R%a');
        $lastDate=(\substr($dateIntervalStr,0,1)=="+" ||\substr($dateIntervalStr,1,1)=="0" )?$this->getFirstDayButNotInWeekEnd():$lastDate;
        $date=$this->getAppropriateDate($lastDate);
        return $date;
        

}
public function getFirstDayButNotInWeekEnd()
{
    $lastDate=\date_add(\date_create(\date("Y-m-d")), date_interval_create_from_date_string('1 days'));
   $lastDate=($lastDate->format("D")=='Sat')?\date_add($lastDate, date_interval_create_from_date_string('2 days')):$lastDate;
    $lastDate=($lastDate->format("D")=='Sun')?\date_add($lastDate, date_interval_create_from_date_string('1 days')):$lastDate;
    return $lastDate->format("Y-m-d");
    

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
      //$amountAnswers=($day=='Fri')?$this->container->get("esconfig_manager")->getAutoAnswersAmount()*3:$this->container->get("esconfig_manager")->getAutoAnswersAmount();
      $demandesCount=count($this->getDoctrine()->getEntityManager()->getRepository("AppBackOfficeBundle:Demande")->findByDateReponce(\date_create($date)));
        
      if($demandesCount){
        return true;
      }
      return false;
      //$this->container->get("esconfig_manager")->getAutoAnswersAmount();
  }

    public function listedemandeAction() {

        $date="none";
        if($this->container->get("esconfig_manager")->getAutoAnswersStatus()=='activate'){
         $date=$this->getAutoDateReponce();
         $date=$date->format("d-m-Y");
        }
   
        
        $demandes = $this->getDemandeToList();
        
        return $this->render(
            'AppBackOfficeBundle:Demande:listedemande.html.twig', 
            array('demandes' => $demandes,"date"=>$date,"autoReponceAmount"=>($this->container->get("esconfig_manager")->getAutoAnswersAmount()>count($demandes))?count($demandes):$this->container->get("esconfig_manager")->getAutoAnswersAmount()
            )
            );
    }
    
private function getDemandeToList()
{ 
     $admin = $this->getUser();
        $em = $this->getDoctrine()->getEntityManager();
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
        ->Where($qb->expr()->eq('d.status', '?1'))
        ->andWhere($qb->expr()->notIn('d.typeDemande', '?2'))
        ->setParameter(1, 0)
        ->setParameter(2, $typedemandes);
        return $qb->getQuery()->getResult();
}
    
    public function traiterdemandeAction($id) {

      $em = $this->getDoctrine()->getEntityManager();

      $admin = $this->getUser();

      $repDemande = $this->getDoctrine()->getRepository('AppBackOfficeBundle:Demande');
      $demande = $repDemande->findOneById($id);

      if($this->get('request')->request->get('rejeter') == 'rejeter'){

        $qb = $em->createQueryBuilder();
        $qb->update('App\Bundle\BackOfficeBundle\Entity\Demande', 'd')
        ->set('d.status', '?1')
        ->set('d.updatedAt', '?3')
        ->set('d.notified', '?4')
        ->where($qb->expr()->eq('d.id', '?2'))
        ->setParameter(1, 2)
        ->setParameter(2, $id)
        ->setParameter(4, 0)
        ->setParameter(3, new \DateTime());
        $test = $qb->getQuery()->getResult();

        $qq = $em->createQueryBuilder();
        $qq->update('App\Bundle\BackOfficeBundle\Entity\EtatDemande', 'e')
        ->set('e.etat', '?1')
        ->set('e.admin', '?2')
        ->where($qq->expr()->eq('e.demande', '?3'))
        ->setParameter(1, 'Rejeter')
        ->setParameter(2, $admin)
        ->setParameter(3, $demande);

        $test = $qq->getQuery()->getResult();

        return $this->redirect($this->get('router')->generate('listedemande', array()));
       

    } elseif ($this->get('request')->request->get('fixer') == 'fixer'){
          
        if(strlen( $this->get('request')->request->get('rv'))==0){
           $repDemande = $this->getDoctrine()->getRepository('AppBackOfficeBundle:Demande');
           $demande = $repDemande->findOneById($id);
           $error=1;

           return $this->render( 
            'AppBackOfficeBundle:Demande:traiterdemande.html.twig', 
            array( 'demande' => $demande ,'error'=>$error)
            );}
           $justification = $this->get('request')->request->get('justification');
           $s = $this->get('request')->request->get('rv');
                /*03/25/2014
                2015-08-30*/
                $rv = \DateTime::createFromFormat('d-m-Y', $s)->format('Y-m-d');
               // $rv = substr($s, 6, 4) . "-" . substr($s, 0, 2) . "-" . substr($s, 3, 2);
                $qb = $em->createQueryBuilder();
                
                $qb->update('App\Bundle\BackOfficeBundle\Entity\Demande', 'd')
                ->set('d.status', '?1')
                ->set('d.updatedAt', '?3')
                ->set('d.dateReponce', '?4')

                ->set('d.notified', '?5')
                ->where($qb->expr()->eq('d.id', '?2'))
                ->setParameter(1, 1)
                ->setParameter(2, $id)
                ->setParameter(3, new \DateTime())
                ->setParameter(5, 0)
                ->setParameter(4, new \DateTime($rv));
                $test = $qb->getQuery()->getResult();
                
                
                $qq = $em->createQueryBuilder();
                $qq->update('App\Bundle\BackOfficeBundle\Entity\EtatDemande', 'e')
                ->set('e.etat', '?1')
                ->set('e.admin', '?2')
                ->set('e.justification', '?4')
                ->where($qq->expr()->eq('e.demande', '?3'))
                ->setParameter(1, 'Traiter')
                ->setParameter(2, $admin)
                ->setParameter(3, $demande)
                ->setParameter(4, $justification);

                $test = $qq->getQuery()->getResult();
                
                return $this->redirect($this->get('router')->generate('listedemande', array()));            
            } 

            $repDemande = $this->getDoctrine()->getRepository('AppBackOfficeBundle:Demande');
            $demande = $repDemande->findOneById($id);
            $error=0;


            return $this->render( 
                'AppBackOfficeBundle:Demande:traiterdemande.html.twig', 
                array( 'demande' => $demande ,'error'=>$error)
                );
        }
       
        public function reponceAutoAction()
        {
            // $date=new \DateTime('now');

            // $dateInterval=(\date_create("2014-04-01")->diff($date));
            // $dateIntervalStr=$dateInterval->format('%R%a');
            // return new Response($dateIntervalStr);     
            //      ->set('d.status', '?1')
                // ->set('d.updatedAt', '?3')
                // ->set('d.dateReponce', '?4')

                // ->set('d.notified', '?5')
                // 
                // ->setParameter(1, 1)
                //
                // ->setParameter(3, new \DateTime())
                // ->setParameter(5, 0)
                // ->setParameter(4, new \DateTime($rv));
             $em = $this->getDoctrine()->getEntityManager();
            $rv = \DateTime::createFromFormat('d-m-Y', $this->get('request')->request->get('rv'))->format('Y-m-d');
           $demandeWithStatusZero=$em->getRepository("AppBackOfficeBundle:Demande")->findByStatus(0);
           $lenght=($this->container->get("esconfig_manager")->getAutoAnswersAmount()>count($demandeWithStatusZero))?count($demandeWithStatusZero):$this->container->get("esconfig_manager")->getAutoAnswersAmount();
            for($i=0;$i<$lenght;$i++){
                $demandeWithStatusZero[$i]->setStatus(1);
                $demandeWithStatusZero[$i]->setUpdatedAt(new \DateTime());
                $demandeWithStatusZero[$i]->setDateReponce(\date_create($rv));
                $demandeWithStatusZero[$i]->setNotified(0);
                $em->persist($demandeWithStatusZero[$i]);
                $em->flush();
                $this->updateEtademande($demandeWithStatusZero[$i]);

            }
           //return new Response($demandeWithStatusZero[0]->getStatus());
           // return \getType($rv);
           return $this->redirect($this->generateUrl('listedemande'));
         
        }
        public function updateEtademande($demande)
        {  $em = $this->getDoctrine()->getEntityManager();

            $admin=$this->getUser();
            $qq = $em->createQueryBuilder();
                $qq->update('App\Bundle\BackOfficeBundle\Entity\EtatDemande', 'e')
                ->set('e.etat', '?1')
                ->set('e.admin', '?2')
                ->where($qq->expr()->eq('e.demande', '?3'))
                ->setParameter(1, 'Traiter')
                ->setParameter(2, $admin)
                ->setParameter(3, $demande);

            $qq->getQuery()->getResult();
        }

    }

