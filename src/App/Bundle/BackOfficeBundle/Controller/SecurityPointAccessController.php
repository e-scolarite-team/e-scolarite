<?php

namespace App\Bundle\BackOfficeBundle\Controller;

use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\HttpFoundation\Response as HttpResponse;

use Symfony\Component\Routing\Router;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\SecurityContext;
use App\Bundle\BackOfficeBundle\Entity\Admin;

class SecurityPointAccessController extends Controller
{
    public function loginAction()
    {

    	/****
    	$enFactory = $this->get('security.encoder_factory');
		
		$admin = new Admin();
		$admin->setEmail("admin@gmail.com")->setNom("Admin")->setPrenom("admin");
		$admin->addRole('ROLE_ADMIN');
		
		$encoder = $enFactory->getEncoder($admin);
		
		$admin->setPassword($encoder->encodePassword('admin',$admin->getSalt()));
		
		$em = $this->get("doctrine")->getEntityManager();
		
		$em->persist($admin);
		
		$em->flush();
    	***/
    	$request = $this->getRequest();
		$session = $request->getSession();
	
		// get the login error if there is one
		if ($request->attributes->has(SecurityContext::AUTHENTICATION_ERROR)) {
			$errors = $request->attributes->get(
					SecurityContext::AUTHENTICATION_ERROR
			);
		} else {
			$errors = $session->get(SecurityContext::AUTHENTICATION_ERROR);
			$session->remove(SecurityContext::AUTHENTICATION_ERROR);
		}
	
		return $this->render(
				'AppBackOfficeBundle:SecurityPointAccess:login.html.twig',
				array(
						// last username entered by the user
						'last_username' => $session->get(SecurityContext::LAST_USERNAME),
						'errors'         => $errors,
				)
		);
    }

}
