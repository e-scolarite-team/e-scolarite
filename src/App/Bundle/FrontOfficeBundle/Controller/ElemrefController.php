<?php

namespace App\Bundle\FrontOfficeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use App\Bundle\BackOfficeBundle\Entity\Demande;
use App\Bundle\BackOfficeBundle\Entity\TypeDemande;
use App\Bundle\BackOfficeBundle\Entity\Etudiant;
use App\Bundle\BackOfficeBundle\Entity\EtatDemande;
use App\Bundle\BackOfficeBundle\Entity\Admin;
use App\Bundle\BackOfficeBundle\Form\Data\ImportData;
use App\Bundle\BackOfficeBundle\Form\ImportFormType;


class ElemrefController extends Controller {

    // --------------------------------------------------------------------
    
    
    public function indexAction() {
        $session = 1;
        $annee = 2012;
        $op = array();
        if($session == 1){$op = array("01","03","05");}
            else $op = array("02","04","06");
        $em = $this->getDoctrine()->getEntityManager();
        $etudiant = $this->getUser();
        $modulesElementRef = array();
        $semestres = $em->getRepository("AppBackOfficeBundle:ResultatElp")->getSemestres($annee, $etudiant, $em, $op);
        $semestreActuel = $semestres[0]->getElement();
        
            $modules = $em->getRepository("AppBackOfficeBundle:ResultatElp")->getModules( $etudiant, $em, $semestreActuel);
            foreach($modules as $module){
                $elements = $em->getRepository("AppBackOfficeBundle:ResultatElp")->getElements( $etudiant, $em, $module->getElement());
                foreach ($elements as $element) {
                    $note = $element->getNote();
                    if($note >= 10) {
                        //return new Response($module->getElement()->getCode());
                        $modulesElementRef[] = $module->getElement();
                        break;
                    }
                }
            }

        return $this->render(
                                'AppFrontOfficeBundle:ElemRef:ElemRefEtu.html.twig', 
                                array('modules' => $modulesElementRef)
                            );
    }
    
   
    
    
}

