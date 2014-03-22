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

        $cne='2927645118'; // recuperer cne d'un etudiant
        $e = $em->getRepository('AppBackOfficeBundle:Etudiant')->findByCne($cne);
       // return new Response(var_dump($e[0]));
        $demandes = $em->getRepository('AppBackOfficeBundle:Demande')->findBy(array('etudiant' => $e[0], 'status' => '1', 'notified' => '0' ) );
        $reclamations = $em->getRepository('AppBackOfficeBundle:Reclamation')->findBy(array('etudiant' => $e[0], 'status' => '1', 'notified' => '0'));

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

        $cne='2927645118'; // recuperer cne d'un etudiant
        $e = $em->getRepository('AppBackOfficeBundle:Etudiant')->findByCne($cne);

        $entities1 =$em->getRepository('AppBackOfficeBundle:Demande')->findBy(array('etudiant' => $e[0], 'status' => '1', 'notified' => '0') );
        $demandes=array();$demandes["id"]=array(); $demandes["type"]=array(); $demandes["createdAt"]=array();
       
        for($i=0 ; $i<count($entities1); $i++) {
            array_push($demandes["id"], $entities1[$i]->getId());
            array_push($demandes["type"], $entities1[$i]->getTypeDemande()->getLibelle()); 
            array_push($demandes["createdAt"], $entities1[$i]->getCreatedAt()->format('d-m-Y H:i'));               
        }
        
        $entities2 = $em->getRepository('AppBackOfficeBundle:Reclamation')->findBy(array('etudiant' => $e[0], 'status' => '1', 'notified' => '0'));
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


        // redirection vers la demande selectionnée  
    }


    
    public function toreclamationAction($id){
        $em = $this->getDoctrine()->getManager();

        $reclamation = $em->getRepository('AppBackOfficeBundle:Reclamation')->find($id);
        $reclamation->setNotified(1);
        $em->persist($reclamation);
        $em->flush();

       // redirection vers la reclamation selectionnée    
    }

}
