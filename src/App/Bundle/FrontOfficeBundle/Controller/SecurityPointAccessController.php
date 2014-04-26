<?php

namespace App\Bundle\FrontOfficeBundle\Controller;

use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\HttpFoundation\Response as HttpResponse;

use Symfony\Component\Routing\Router;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\SecurityContext;
use App\Bundle\BackOfficeBundle\Entity\Etudiant;

class SecurityPointAccessController extends Controller
{
    public function loginAction()
    {
		
		
		/*$Etudiant = new Etudiant($this->container);
		$Etudiant->setCne("2927531476")->setCodeAppogee("30000")->setId("11234");
		$Etudiant->setCin("h34547")->setNom("Kda")->setPrenom("karim");
		$Etudiant->setDateNaissance(new \DateTime())->setVilleNaissance("safi")->setPrenomAr("11234");
		$Etudiant->setAdresse("fshhs kks")->setAnneeInscription(2012)->setNomAr("11234")->setSexe("M");
		$Etudiant->preparePassword();
		
		$em = $this->get("doctrine")->getEntityManager();
		
		$em->persist($Etudiant);
		
		$em->flush();
		*/
		
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
				'AppFrontOfficeBundle:SecurityPointAccess:login.html.twig',
				array(
						// last username entered by the user
						'last_username' => $session->get(SecurityContext::LAST_USERNAME),
						'errors'         => $errors,
				)
		);
    }

}
