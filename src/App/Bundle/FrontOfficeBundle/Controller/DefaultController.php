<?php

namespace App\Bundle\FrontOfficeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('AppFrontOfficeBundle:Default:index.html.twig', array('name' => $name));
    }
}
