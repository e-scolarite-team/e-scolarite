<?php

namespace App\Bundle\FrontOfficeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use App\Bundle\BackOfficeBundle\Entity\Reclamation;
use App\Bundle\BackOfficeBundle\Entity\Etudiant;
use App\Bundle\FrontOfficeBundle\Form\ReclamationFormType;

class ReclamationController extends Controller
{
	/**
     * Lists all TypeDemande entities.
     *
     */
    public function indexAction()
    {
        
        return $this->render('AppFrontOfficeBundle:Reclamation:index.html.twig', array(
            'entities' => $this->getUser()->getReclamations(),
        ));

    }

    public function envoyerAction(Request $request){

        $etudiant = $this->getUser();

        $message = "";
        
    	$entity = new Reclamation();            	    

    	$em = $this->getDoctrine()->getManager();        
        
        $form = $this->createForm(new ReclamationFormType(),$entity);
        
        $errors = array();

        if ($request->isMethod('POST')) {
        	$form->handleRequest($request);
            $validator = $this->get("validator");
            
            $translator  = $this->get('translator');
            
            $entity->setEtudiant($etudiant);

            $d =  new \DateTime();
            $d->format('Y-m-d');
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

        $i = 0;

        foreach ($etudiant->getReclamations() as $rec) {
            
            if($rec->getCreatedAt() < $date_fin && $rec->getCreatedAt() > $date_debut) $i++;

        }
        
           //if(count($errList)>0)return new Response(var_dump(count($errList)));
            $errList = $validator->validate($form);        
        if($i >= $entity->getTypeReclamation()->getMaxAutorise()) $message = "Vous avez atteindre le nombre de reclamation autorise";
            if(count($errList) > 0 || $message != ""){
            
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
            'message'=> $message,
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
