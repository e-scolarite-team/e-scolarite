<?php

namespace App\Bundle\BackOfficeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use App\Bundle\BackOfficeBundle\Entity\Reclamation;
use App\Bundle\BackOfficeBundle\Form\ReponseReclamationType;
use Symfony\Component\Validator\Constraints as Assert;

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
            
        $data = array("reponse"=>"");
        
        $form = $this->createFormBuilder($data)
            ->add("reponse","textarea")
            ->getForm();
        
        $errors = array();

        $request = $this->get("request");

        if ($request->isMethod('POST')) {
        	
            $form->handleRequest($request);
            
            if($this->getRequest()->request->get('status') == 'valider'){
                $entity->setStatus(1);
            }
            elseif ($this->getRequest()->request->get('status') == 'refuser') {
                $entity->setStatus(-1);
            }

            $entity->setConsultedAt(new \DateTime());

            $em = $this->getDoctrine()->getManager();
            //return new Response(var_dump($this->getUser()));
            $entity->setAdmin($this->getUser());
            
            $notBlank = new Assert\NotBlank();

            $notBlank->message = 'errors.reclamation.reponse';   

            $reponse = $form->getData()["reponse"];            

            $validator = $this->get("validator");
            
            $translator  = $this->get('translator');

            $errList = $validator->validateValue($reponse, $notBlank);        
                       
            if(count($errList) > 0){
            
                foreach ($errList as $err) 
                   $errors[] =  $translator->trans($err->getMessage(),array(),'messages','fr_FR');
                
            }else{
                $entity->setReponse($reponse);
                $entity->setNotified(0);
                
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
