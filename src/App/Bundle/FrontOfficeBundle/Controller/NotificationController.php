<?php

namespace App\Bundle\FrontOfficeBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use App\Bundle\BackOfficeBundle\Entity\Demande;
use App\Bundle\BackOfficeBundle\Entity\Reclamation;



class NotificationController extends Controller{







    public function notificationetudiantAction(){
        $em = $this->getDoctrine()->getManager();
        
        $cne= $this->getUser()->getCne();
        $e = $em->getRepository('AppBackOfficeBundle:Etudiant')->findByCne($cne);
        $qb = $em->createQueryBuilder();
        
        $qb->select('d')
        ->from('App\Bundle\BackOfficeBundle\Entity\Demande', 'd')
        ->where($qb->expr()->eq('d.etudiant', '?1'))
        ->andWhere($qb->expr()->eq('d.notified', '?2'))
        ->andWhere($qb->expr()->neq('d.status', '?3'))
        ->setParameter(1, $e[0])
        ->setParameter(2, 0)
        ->setParameter(3, 0);

        $demandes = $qb->getQuery()->getResult();

        $qb->select('r')
        ->from('App\Bundle\BackOfficeBundle\Entity\Reclamation', 'r')
        ->where($qb->expr()->eq('r.etudiant', '?1'))
        ->andWhere($qb->expr()->eq('r.notified', '?2'))
        ->andWhere($qb->expr()->neq('r.status', '?3'))
        ->setParameter(1, $e[0])
        ->setParameter(2, 0)
        ->setParameter(3, 0);

        $reclamations = $qb->getQuery()->getResult();

        return $this->render('AppFrontOfficeBundle:Notification:notification.html.twig', array(
                'demandes' => $demandes,
                'reclamations' => $reclamations,
            ));
    }

    
    
    public function vudemandeAction($id){
        $em = $this->getDoctrine()->getManager();

        $demande = $em->getRepository('AppBackOfficeBundle:Demande')->find($id);
        $demande->setNotified(1);
        $em->persist($demande);
        $em->flush();

        return $this->redirect($this->generateUrl('notification-etudiant'));
         
    }


    
    public function vureclamationAction($id){
        $em = $this->getDoctrine()->getManager();

        $reclamation = $em->getRepository('AppBackOfficeBundle:Reclamation')->find($id);
        $reclamation->setNotified(1);
        $em->persist($reclamation);
        $em->flush();

        return $this->redirect($this->generateUrl('notification-etudiant'));
         
    }

    

    public function vunotificationAction(){
        $em = $this->getDoctrine()->getManager();

        $cne=$this->getUser()->getCne(); // recuperer cne d'un etudiant
        $e = $em->getRepository('AppBackOfficeBundle:Etudiant')->findByCne($cne);
        $qb = $em->createQueryBuilder();
       

        $qb->select('d')
        ->from('App\Bundle\BackOfficeBundle\Entity\Demande', 'd')
        ->where($qb->expr()->eq('d.etudiant', '?1'))
        ->andWhere($qb->expr()->eq('d.notified', '?2'))
        ->andWhere($qb->expr()->neq('d.status', '?3'))
        ->setParameter(1, $e[0])
        ->setParameter(2, 0)
        ->setParameter(3, 0);

        $entities1 = $qb->getQuery()->getResult();
        
        $demandes=array();$demandes["id"]=array(); $demandes["type"]=array(); $demandes["createdAt"]=array();
       
        for($i=0 ; $i<count($entities1); $i++) {
            array_push($demandes["id"], $entities1[$i]->getId());
            array_push($demandes["type"], $entities1[$i]->getTypeDemande()->getLibelle()); 
            array_push($demandes["createdAt"], $entities1[$i]->getCreatedAt()->format('d-m-Y H:i'));               
        }
        

        $qb->select('r')
        ->from('App\Bundle\BackOfficeBundle\Entity\Reclamation', 'r')
        ->where($qb->expr()->eq('r.etudiant', '?1'))
        ->andWhere($qb->expr()->eq('r.notified', '?2'))
        ->andWhere($qb->expr()->neq('r.status', '?3'))
        ->setParameter(1, $e[0])
        ->setParameter(2, 0)
        ->setParameter(3, 0);

        $entities2  = $qb->getQuery()->getResult();
        
        $reclamations=array(); $reclamations["id"]=array(); $reclamations["type"]=array();$reclamations["createdAt"]=array();
        
        for($i=0 ; $i<count($entities2); $i++){
            array_push($reclamations["id"], $entities2[$i]->getId()); 
            array_push($reclamations["type"], $entities2[$i]->getTypeReclamation()->getLibelle());
            array_push($reclamations["createdAt"], $entities2[$i]->getCreatedAt()->format('d-m-Y H:i')); 

        }

        
        
        
        $json = json_encode(array(
            'demandes' => $demandes,
            'reclamations' => $reclamations
        ));
        $response = new Response();
        $response->headers->set('Content-Type', 'application/json');
        $response->setContent($json);
        return $response;
         
    }


    public function todemandeAction($id){
        $em = $this->getDoctrine()->getManager();

        $demande = $em->getRepository('AppBackOfficeBundle:Demande')->find($id);
        $demande->setNotified(1);
        $em->persist($demande);
        $em->flush();
        $id = trim($id);
        $route = "showDemandeEtud";
        if($demande->getTypeDemande()->getCode() == 'ER')
            $route = "consulterElemRef";
        if($demande->getTypeDemande()->getCode() == '5M')
            $route = "consulter5Module";
        return $this->redirect($this->get('router')->generate($route,array('id' => $id)));
    }


    
    public function toreclamationAction($id){
        $em = $this->getDoctrine()->getManager();

        $reclamation = $em->getRepository('AppBackOfficeBundle:Reclamation')->find($id);
        $reclamation->setNotified(1);
        $em->persist($reclamation);
        $em->flush();
        $id = trim($id);
        return $this->redirect($this->get('router')->generate('showReclamationEtud',array('id' => $id)));
    }

}
