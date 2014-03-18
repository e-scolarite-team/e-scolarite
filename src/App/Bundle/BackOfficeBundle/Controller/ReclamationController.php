<?php

namespace App\Bundle\BackOfficeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use App\Bundle\BackOfficeBundle\Entity\Reclamation;
use App\Bundle\BackOfficeBundle\Form\ReponseReclamationType;

class ReclamationController extends Controller
{
	/**
     * Lists all TypeDemande entities.
     *
     */
    public function indexAction()
    {   
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('AppBackOfficeBundle:Reclamation')->findAll();

        return $this->render('AppBackOfficeBundle:Reclamation:index.html.twig', array(
            'entities' => $entities,
        ));
    }

    public function repondreAction($id){
    
    	$entity = new Reclamation();
        
    	$types = array();
    	$errors = array();
        $em = $this->getDoctrine()->getManager();
       
        $entity = $em->getRepository('AppBackOfficeBundle:Reclamation')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Reclamation entity.');
        }
        
        $form = $this->createForm(new ReponseReclamationType(),$entity);
        
        $errors = array();

        $request = $this->get("request");

        if ($request->isMethod('POST')) {
        	
            $form->handleRequest($request);
            //return new Response($this->getRequest()->request->get('status'));
            if($this->getRequest()->request->get('status') == 'valider'){
                $entity->setStatus(1);
            }
            elseif ($this->getRequest()->request->get('status') == 'refuser') {
                $entity->setStatus(-1);
            }

            $entity->setConsultedAt(new \DateTime());

            $validator = $this->get("validator");
            
            $translator  = $this->get('translator');

            $errList = $validator->validate($form);        
                       
            if(count($errList) > 0){
            
                foreach ($errList as $err) 
                   $errors[] =  $translator->trans($err->getMessage(),array(),'messages','fr_FR');
                
            }else{
                $em = $this->getDoctrine()->getManager();
                $em->persist($entity);
                $em->flush();
                return $this->redirect($this->generateUrl('listerReclamation'));
            }
        }

        return $this->render('AppBackOfficeBundle:Reclamation:repondre.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
            'errors' => $errors,
        ));
    }


    
}
