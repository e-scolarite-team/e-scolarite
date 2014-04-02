<?php

namespace App\Bundle\BackOfficeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;


class DefaultController extends Controller
{
    public function indexAction($name){
		
		//$this->container->getParameter("date_format")
		return new Response($this->container->get("esconfig_manager")->getAutoAnswersStatus());
        return $this->render('AppBackOfficeBundle:Default:index.html.twig', array('name' => var_dump($this->container)));
    }
}
