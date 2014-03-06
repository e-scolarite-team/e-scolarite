<?php

namespace App\Bundle\BackOfficeBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

use App\Bundle\BackOfficeBundle\Entity\TypeReclamation;
use App\Bundle\BackOfficeBundle\Form\TypeReclamationType;

/**
 * TypeReclamation controller.
 *
 */
class TypeReclamationController extends Controller
{

    /**
     * Lists all TypeReclamation entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('AppBackOfficeBundle:TypeReclamation')->findAll();

        return $this->render('AppBackOfficeBundle:TypeReclamation:index.html.twig', array(
            'entities' => $entities,
        ));
    }
    /**
     * Creates a new TypeReclamation entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new TypeReclamation();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('type-reclamation_show', array('id' => $entity->getId())));
        }

        return $this->render('AppBackOfficeBundle:TypeReclamation:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
    * Creates a form to create a TypeReclamation entity.
    *
    * @param TypeReclamation $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createCreateForm(TypeReclamation $entity)
    {
        $form = $this->createForm(new TypeReclamationType(), $entity, array(
            'action' => $this->generateUrl('type-reclamation_create'),
            'method' => 'POST',
        ));

        

        return $form;
    }

    /**
     * Displays a form to create a new TypeReclamation entity.
     *
     */
    public function newAction(Request $request)
    {
        $entity = new TypeReclamation();

        $form   = $this->createCreateForm($entity);

        $errors = array();

        if ($request->isMethod('POST')){

            $form->handleRequest($request);

            $validator = $this->get("validator");                        

            $errList = $validator->validate($form);        

            if(count($errList) > 0){
            
                foreach ($errList as $err) {
                   
                   $errors[] = $err->getMessage();
                
                }
                
            }else{
               
                $em = $this->getDoctrine()->getManager();                

                $em->persist($entity);
                
                $em->flush();

                return $this->redirect($this->generateUrl('type-reclamation'));
                
            }
                
            
        
        }
        return $this->render('AppBackOfficeBundle:TypeReclamation:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
            'errors' => $errors
        ));
    }

    /**
     * Finds and displays a TypeReclamation entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBackOfficeBundle:TypeReclamation')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find TypeReclamation entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('AppBackOfficeBundle:TypeReclamation:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),        ));
    }

    /**
     * Displays a form to edit an existing TypeReclamation entity.
     *
     */
    public function editAction($id)
    {
        
        $em = $this->getDoctrine()->getManager();
        $errors = array();
        $entity = $em->getRepository('AppBackOfficeBundle:TypeReclamation')->find($id);
        //return new Response(var_dump($entity));
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find TypeReclamation entity.');
        }

        $form = $this->createEditForm($entity);
        //$deleteForm = $this->createDeleteForm($id);
        
        return $this->render('AppBackOfficeBundle:TypeReclamation:edit.html.twig', array(
            'entity'      => $entity,
            'form'   => $form->createView(),
            //'delete_form' => $deleteForm->createView(),
            'errors'      => $errors,
        ));
    }

    /**
    * Creates a form to edit a TypeReclamation entity.
    *
    * @param TypeReclamation $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(TypeReclamation $entity)
    {
        $form = $this->createForm(new TypeReclamationType(), $entity, array(
            'action' => $this->generateUrl('type-reclamation_update', array('id' => $entity->getId())),
            'method' => 'POST',
        ));

        

        return $form;
    }
    /**
     * Edits an existing TypeReclamation entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBackOfficeBundle:TypeReclamation')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find TypeReclamation entity.');
        }

        //$deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
       
        $errors = array();
        if ($request->isMethod('POST')){

            $editForm->handleRequest($request);

            $validator = $this->get("validator");                        

            $errList = $validator->validate($editForm);        

            if(count($errList) > 0){
            
                foreach ($errList as $err) {
                   
                   $errors[] = $err->getMessage();
                
                }
                
            }else{
               
                $em = $this->getDoctrine()->getManager();                

                $em->persist($entity);
                
                $em->flush();
                
            }
        }
        

        return $this->render('AppBackOfficeBundle:TypeReclamation:edit.html.twig', array(
            'entity'      => $entity,
            'form'   => $editForm->createView(),
            //'delete_form' => $deleteForm->createView(),
            'errors'      => $errors,  
        ));
    }
    /**
     * Deletes a TypeReclamation entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {

        //$form = $this->createDeleteForm($id);
        ///$form->handleRequest($request);
//return new Response(var_dump($form));
        //if ($form->isValid()) {

            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('AppBackOfficeBundle:TypeReclamation')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find TypeReclamation entity.');
            }

            $em->remove($entity);
            $em->flush();
        //}

        return $this->redirect($this->generateUrl('type-reclamation'));
    }

    /**
     * Creates a form to delete a TypeReclamation entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('type-reclamation_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
