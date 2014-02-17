<?php

namespace MVC\Controllers;

use Api\MVC\Controller\BaseController;
use Api\MVC\View\Html\HtmlView;
use MVC\Models\Entity\Client;


class IndexController extends BaseController{

	public function indexAction(){

		$articles = $this->get('orm.manager')->getStore('MVC\Models\Entity\Article')->findAll();
		$rubriques = $this->get('orm.manager')->getStore('MVC\Models\Entity\Rubrique')->findAll();

		$view = new HtmlView();
		$view->setLayout('_template/site');
		$view->setView('_template/index');
		$view->controller = $this->getName();

		$view->articles = $articles;
		$view->rubriques = $rubriques;
		
		
		
		return $view;
	}


	public function byThemeAction($theme){

		$articles = $this->get('orm.manager')->getStore('MVC\Models\Entity\Article')->findByTheme($theme);
		$rubriques = $this->get('orm.manager')->getStore('MVC\Models\Entity\Rubrique')->findAll();

		$view = new HtmlView();
		$view->setLayout('_template/site');
		$view->setView('_template/index');
		$view->controller = $this->getName();

		$view->articles = $articles;
		$view->rubriques = $rubriques;
		
		
		
		return $view;
	}

	public function checkoutAction(){

		$rubriques = $this->get('orm.manager')->getStore('MVC\Models\Entity\Rubrique')->findAll();

		$view = new HtmlView();
		$view->setLayout('_template/site');
		$view->setView('_template/checkout');
		$view->controller = $this->getName();

		$view->rubriques = $rubriques;

		$commande = array();

		if($this->get('session')->has('commande'))
			$commande = $this->get('session')->get('commande');

		$view->commande = $commande;
		
		
		
		return $view;
	}

	public function subscribAction(){

		$rubriques = $this->get('orm.manager')->getStore('MVC\Models\Entity\Rubrique')->findAll();

		$client = new Client();
		
		$errors = null;

		if($this->request->isPost()){

			$data = $this->request->getPost('client');

			$client->push($data);

			$errors = Validator::validate($client->getValidatorFormat());

			if(count($errors) == 0){
				$this->get('orm.manager')->save($client);
				return $this->redirectTo('home');
			}
		}

		$view = new HtmlView();
		$view->setLayout('_template/site');
		$view->setView('_template/subscrib');
		$view->controller = $this->getName();
		$view->rubriques = $rubriques;

		$view->form = $client->toArray();
		$view->errors = $errors;
		
		return $view;

	}
	

}