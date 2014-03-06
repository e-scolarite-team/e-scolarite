<?php

namespace App\Bundle\BackOfficeBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use App\Bundle\BackOfficeBundle\Entity\TypeDemande;
use App\Bundle\BackOfficeBundle\Form\TypeDemandeType;

/**
 * TypeDemande controller.
 *
 */
class TypeDemandeController extends Controller
{

    /**
     * Lists all TypeDemande entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('AppBackOfficeBundle:TypeDemande')->findAll();

        return $this->render('AppBackOfficeBundle:TypeDemande:index.html.twig', array(
            'entities' => $entities,
        ));
    }
    /**
     * Creates a new TypeDemande entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new TypeDemande();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);
        $errors = array();

        if ($request->isMethod('POST')) {

            $validator = $this->get("validator");
            
            $translator  = $this->get('translator');

            $errList = $validator->validate($form);        

            if(count($errList) > 0){
            
                foreach ($errList as $err) 
                   $errors[] =  $translator->trans($err->getMessage(),array(), 'messages', 'fr_FR');
                
            }else{
                $em = $this->getDoctrine()->getManager();
                $em->persist($entity);
                $em->flush();
                return $this->redirect($this->generateUrl('type-demande_show', array('id' => $entity->getId())));
            }
        }

        return $this->render('AppBackOfficeBundle:TypeDemande:new.html.twig', array(
            'entity' => $entity,
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
    private function createCreateForm(TypeDemande $entity)
    {
        $form = $this->createForm(new TypeDemandeType(), $entity, array(
            'action' => $this->generateUrl('type-demande_create'),
            'method' => 'POST',
        ));

        return $form;
    }

    /**
     * Displays a form to create a new TypeDemande entity.
     *
     */
    public function newAction()
    {
        $entity = new TypeDemande();
        $form   = $this->createCreateForm($entity);
        $errors = array();

        return $this->render('AppBackOfficeBundle:TypeDemande:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
            'errors'   => $errors,
        ));
    }

    /**
     * Finds and displays a TypeDemande entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBackOfficeBundle:TypeDemande')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find TypeDemande entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('AppBackOfficeBundle:TypeDemande:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),        ));
    }

    /**
     * Displays a form to edit an existing TypeDemande entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBackOfficeBundle:TypeDemande')->find($id);

        $errors = array();

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find TypeDemande entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('AppBackOfficeBundle:TypeDemande:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
            'errors' => $errors,
        ));
    }

    /**
    * Creates a form to edit a TypeDemande entity.
    *
    * @param TypeDemande $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(TypeDemande $entity)
    {
        $form = $this->createForm(new TypeDemandeType(), $entity, array(
            'action' => $this->generateUrl('type-demande_update', array('id' => $entity->getId())),
            'method' => 'POST',
        ));

        return $form;
    }
    /**
     * Edits an existing TypeDemande entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBackOfficeBundle:TypeDemande')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find TypeDemande entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        $errors = array();

        if ($request->isMethod('POST')) {

            $validator = $this->get("validator");
            
            $translator  = $this->get('translator');

            $errList = $validator->validate($editForm);        

            if(count($errList) > 0){
            
                foreach ($errList as $err) 
                   $errors[] =  $translator->trans($err->getMessage(),array(), 'messages', 'fr_FR');
                
            }else{
                $em = $this->getDoctrine()->getManager();
                $em->persist($entity);
                $em->flush();
                return $this->redirect($this->generateUrl('type-demande_show', array('id' => $entity->getId())));
            }
        }

        return $this->render('AppBackOfficeBundle:TypeDemande:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
            'errors' => $errors ,
        ));
    }
    /**
     * Deletes a TypeDemande entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('AppBackOfficeBundle:TypeDemande')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find TypeDemande entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('type-demande'));
    }

    /**
     * Deletes a TypeDemande entity.
     *
     */
    public function deleteGetAction(Request $request, $id)
    {
        
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('AppBackOfficeBundle:TypeDemande')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find TypeDemande entity.');
        }

        $em->remove($entity);
        $em->flush();
        

        return $this->redirect($this->generateUrl('type-demande'));
    }

    /**
     * Creates a form to delete a TypeDemande entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('type-demande_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
