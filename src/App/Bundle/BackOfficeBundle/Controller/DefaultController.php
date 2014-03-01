<?php

namespace App\Bundle\BackOfficeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use App\Bundle\BackOfficeBundle\Entity\Note;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('AppBackOfficeBundle:Default:index.html.twig', array('name' => $name));
    }
	
	private function sayHello(){
		echo "je suis es1";
	}
}
