<?php

namespace App\Bundle\BackOfficeBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use App\Bundle\BackOfficeBundle\Entity\Demande;
use App\Bundle\BackOfficeBundle\Entity\Reclamation;



class NotificationController extends Controller
{

    
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $demandes = $em->getRepository('AppBackOfficeBundle:Demande')->findBy(array('status'=>'0','notified'=>'0'));
        $reclamations = $em->getRepository('AppBackOfficeBundle:Reclamation')->findBy(array('status'=>'0','notified'=>'0'));

        
        return $this->render('AppBackOfficeBundle:Notification:notification.html.twig', array(
            'demandes' => $demandes,'reclamations' => $reclamations,
        ));
    }


    public function vuAction($id)
    {
    	$em = $this->getDoctrine()->getManager();

        $demande = $em->getRepository('AppBackOfficeBundle:Demande')->find($id);
        $demande->setNotified(1);
        $em->persist($demande);
		$em->flush();
		return $this->redirect($this->generateUrl('notification-admin'));
    	 
    }
    public function vureclamationAction($id)
    {
    	$em = $this->getDoctrine()->getManager();

        $reclamation = $em->getRepository('AppBackOfficeBundle:Reclamation')->find($id);
        $reclamation->setNotified(1);
        $reclamation->setConsultedAt(new \DateTime());
        $em->persist($reclamation);
		$em->flush();
		return $this->redirect($this->generateUrl('notification-admin'));
    	 
    }
    

      public function barrenotificationAction()
    {
        //if($this->get('security.context')->isGranted('ROLE_SUPER_USER'))
        $em = $this->getDoctrine()->getManager();
        $demandes = $em->getRepository('AppBackOfficeBundle:Demande')->findBy(array('status' => '0', 'notified' => '0'));
        $reclamations = $em->getRepository('AppBackOfficeBundle:Reclamation')->findBy(array('status' => '0', 'notified' => '0'));
        $array_demandes=array(); 
        $array_demandes["id"]=array(); $array_demandes["nom"]=array(); $array_demandes["prenom"]=array();$array_demandes["type"]=array();$array_demandes["createdAt"]=array();
        $array_reclamations=array(); 
        $array_reclamations["id"]=array(); $array_reclamations["nom"]=array(); $array_reclamations["prenom"]=array();$array_reclamations["type"]=array();$array_reclamations["createdAt"]=array();
        
        
        for($i=0 ; $i<count($demandes); $i++) 
            {
            array_push($array_demandes["id"], $demandes[$i]->getId());
            array_push($array_demandes["type"], $demandes[$i]->getTypeDemande()->getLibelle()); 
            array_push($array_demandes["nom"], $demandes[$i]->getEtudiant()->getNom()); 
            array_push($array_demandes["prenom"], $demandes[$i]->getEtudiant()->getPrenom());
            array_push($array_demandes["createdAt"], $demandes[$i]->getCreatedAt()->format('d-m-Y H:i')); 

              
          }
        for($i=0 ; $i<count($reclamations); $i++) 
            {
            array_push($array_reclamations["id"], $reclamations[$i]->getId());
            array_push($array_reclamations["type"], $reclamations[$i]->getTypeReclamation()->getLibelle()); 
            array_push($array_reclamations["nom"], $reclamations[$i]->getEtudiant()->getNom()); 
            array_push($array_reclamations["prenom"], $reclamations[$i]->getEtudiant()->getPrenom());
            array_push($array_reclamations["createdAt"], $reclamations[$i]->getCreatedAt()->format('d-m-Y H:i')); 

              
          }
        
        
        $json = json_encode(array(
            'demandes' => $array_demandes,
            'reclamations' => $array_reclamations
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

        $route = 'traiterdemande';
        if($demande->getTypeDemande()->getCode() == 'CM')
            $route = 'traiterdemandechangement';

        return $this->redirect($this->generateUrl($route,array('id' =>  trim($id) )));
    }


    
    public function toreclamationAction($id){

        $em = $this->getDoctrine()->getManager();

        $reclamation = $em->getRepository('AppBackOfficeBundle:Reclamation')->find($id);
        $reclamation->setNotified(1);
        $em->persist($reclamation);
        $em->flush();

       return $this->redirect($this->generateUrl('repondreReclamation',array('id' => trim($id))));
    }
}
