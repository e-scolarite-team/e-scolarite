<?php

namespace App\Bundle\BackOfficeBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use App\Bundle\BackOfficeBundle\Form\ConfigType;
use App\Bundle\BackOfficeBundle\Form\Data\ConfigData;


class ConfigController extends Controller
{
	
	public function configAction(Request $request){

		$errors = array();
        $data  = new ConfigData();
        $this->container->get("esconfig_manager")->fill($data);

        $form  = $this->createForm(new ConfigType($data->getInfoSemester()),$data);

        

        if ($request->isMethod('POST')){

            $form->handleRequest($request);

            $validator = $this->get("validator");
            
            $translator  = $this->get('translator');

            $errList = $validator->validate($form);        

            if(count($errList) > 0){

                foreach ($errList as $err) {
                	// trans($id, array $parameters = array(), $domain = null, $locale = null)
                	$errors[] =  $translator->trans($err->getMessage(),array(), 'messages', 'fr_FR');

            	}

         }else{
         	$this->container->get("esconfig_manager")->fillFrom($form->getData());
         	$this->container->get("esconfig_manager")->save();
         }
                                 


    	}

		return $this->render("AppBackOfficeBundle:Config:config.html.twig", array('form' => $form->createView(), 'errors' => $errors));
	}
}