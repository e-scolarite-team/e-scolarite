<?php

namespace App\Bundle\BackOfficeBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use App\Bundle\BackOfficeBundle\Entity\Admin;
use App\Bundle\BackOfficeBundle\Form\TypeDemandeType;

/**
 * TypeDemande controller.
 *
 */
class TypeDemandeController extends Controller
{

    /**
     * Lists all Admin .
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
     * Lists create Admin .
     *
     */
    public function createAdminAction()
    {
        $em = $this->getDoctrine()->getManager();
        $admin= new Admin();

        $this->createFormBuilder($admin)
             ->add("nom","text")
             ->add("prenom","text")



    }
   
}
