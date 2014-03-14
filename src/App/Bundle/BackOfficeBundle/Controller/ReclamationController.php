<?php

namespace App\Bundle\BackOfficeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use App\Bundle\BackOfficeBundle\Entity\Reclamation;
use App\Bundle\BackOfficeBundle\Form\ReclamationFormType;

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

    public function envoyerAction(Request $request){
    
    	$entity = new Reclamation();
    	$types = array();
    	$errors = array();
    	$em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository('AppBackOfficeBundle:TypeReclamation')->findAll();
        foreach ($entities as $type) {
        	$types[] = $type->getLibelle();
        }
        
        $form = $this->createForm(new ReclamationFormType(),$entity);
        
        $errors = array();

        if ($request->isMethod('POST')) {
        	$form->handleRequest($request);
            $validator = $this->get("validator");
            
            //$translator  = $this->get('translator');

            $errList = $validator->validate($form);        
                       
            if(count($errList) > 0){
            
                foreach ($errList as $err) 
                   $errors[] =  $err->getMessage();
                
            }else{
                $em = $this->getDoctrine()->getManager();
                $em->persist($entity);
                $em->flush();
                return $this->redirect($this->generateUrl('listerReclamation'));
            }
        }

        return $this->render('AppBackOfficeBundle:Reclamation:envoyer.html.twig', array(
            //'entity' => $entity,
            'form'   => $form->createView(),
            'errors' => $errors,
        ));
    }

    /**
    * Creates a form to create a TypeDemande entity.
    *
    * @param TypeDemande $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createCreateForm(Reclamation $entity)
    {
        $form = $this->createForm(new ReclamationFormType(), $entity, array(
            'action' => $this->generateUrl('reclamation'),
            'method' => 'POST',
        ));

        return $form;
    }

    /**
     * Finds and displays a TypeDemande entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $statusInfo = "panel-primary";
        $statusAlert = "alert-info";
        $estValide = "Cette reclamation est en cours de traitement";
        $entity = $em->getRepository('AppBackOfficeBundle:Reclamation')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find reclamation entity.');
        }

        //$deleteForm = $this->createDeleteForm($id);
        if($entity->getStatus() == -1) {
            $statusInfo = "panel-danger";
            $estValide = "Cette reclamation a été refusée";
            $statusAlert = "alert-danger";
        }
            elseif($entity->getStatus() == 1) {
                $statusInfo = "panel-success";
                $estValide = "Cette reclamation a ete acceptee";
                $statusAlert = "alert-success";
            }
        return $this->render('AppBackOfficeBundle:Reclamation:show.html.twig', array(
            'entity'      => $entity,
            //'delete_form' => $deleteForm->createView(), 
            'statusInfo'      => $statusInfo,
            'estValide'   => $estValide,
            'statusAlert'   => $statusAlert,
                   ));
    }
}
