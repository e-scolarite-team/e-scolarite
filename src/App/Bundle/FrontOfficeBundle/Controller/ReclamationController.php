<?php

namespace App\Bundle\FrontOfficeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use App\Bundle\BackOfficeBundle\Entity\Reclamation;
use App\Bundle\FrontOfficeBundle\Form\ReclamationFormType;

class ReclamationController extends Controller
{
	/**
     * Lists all TypeDemande entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('AppBackOfficeBundle:Etudiant')->find(91279);

        return $this->render('AppFrontOfficeBundle:Reclamation:index.html.twig', array(
            'entities' => $entities->getReclamations(),
        ));
    }

    public function envoyerAction(Request $request){
    
    	$entity = new Reclamation();
    	
    	$errors = array();
    	$em = $this->getDoctrine()->getManager();
        
        
        $form = $this->createForm(new ReclamationFormType(),$entity);
        
        $errors = array();

        if ($request->isMethod('POST')) {
        	$form->handleRequest($request);
            $validator = $this->get("validator");
            
            $translator  = $this->get('translator');
            
            $entity->setEtudiant($em->getRepository("AppBackOfficeBundle:Etudiant")->find(91279));
            //$entity->setEtudiant($em->getRepository("AppBackOfficeBundle:Etudiant")->find($this->getUser()->getId()));
            $errList = $validator->validate($form);        
                       
            if(count($errList) > 0){
            
                foreach ($errList as $err) 
                   $errors[] =  $translator->trans($err->getMessage(), array(), 'messages', 'fr_FR');
                
            }else{
                
                $em->persist($entity);
                $em->flush();
                return $this->redirect($this->generateUrl('listerReclamationEtud'));
            }
        }

        return $this->render('AppFrontOfficeBundle:Reclamation:envoyer.html.twig', array(
            //'entity' => $entity,
            'form'   => $form->createView(),
            'errors' => $errors,
        ));
    }   

    /**
     * Finds and displays a TypeDemande entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();
       
        $entity = $em->getRepository('AppBackOfficeBundle:Reclamation')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find reclamation entity.');
        }

        //$deleteForm = $this->createDeleteForm($id);
        
            
        return $this->render('AppFrontOfficeBundle:Reclamation:show.html.twig', array(
            'entity'      => $entity,
            //'delete_form' => $deleteForm->createView(), 
            
                   ));
    }
}
