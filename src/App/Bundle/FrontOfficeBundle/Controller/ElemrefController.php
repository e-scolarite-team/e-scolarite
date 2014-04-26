<?php

namespace App\Bundle\FrontOfficeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use App\Bundle\BackOfficeBundle\Entity\Demande;
use App\Bundle\BackOfficeBundle\Entity\EtatDemande;
use App\Bundle\BackOfficeBundle\Entity\TypeDemande;
use App\Bundle\BackOfficeBundle\Entity\Etudiant;


class ElemrefController extends Controller {    
    
    public function indexAction() {

        $em = $this->getDoctrine()->getEntityManager();

        $etudiant = $this->getUser();

        $session = $this->container->get("esconfig_manager")->getCurrentSemester();

        $annee = $this->container->get("esconfig_manager")->getCurrentAcademicYear()-1;

        $aucunElementDemande = true;

        $op = array();

        $moduleElements = array();

        $err = "";

        if($session == 1){$op = array("01","03","05");}

            else $op = array("02","04","06");        

        $request = $this->get("request");

        if ($request->isMethod('POST')) {                    

            $em = $this->getDoctrine()->getManager();                                

            $elementsARef = $request->request->get('elements');

            if(count($elementsARef) == 0){

                $err = "Veuillez choisir un element";

            }                                 
                       
            else{

                $entity = new Demande();
                $typeDem = $em->getRepository("AppBackOfficeBundle:TypeDemande")->findBy(array('code'=>'ER'));
                //return new Response(get_class($typeDem[0]));
                $entity->setEtudiant($etudiant);
                $entity->setDonnees($elementsARef);
                $entity->setTypeDemande($typeDem[0]);
                $entity->setNotified(0);
                //$entity->setReponse($reponse);
                
                
                $em->persist($entity);
                $em->flush();
                $etatDemandes =new EtatDemande();
                 $etatDemandes->setEtat("en attente");
                 $etatDemandes->setDemande($entity);
                 $em->persist($etatDemandes);
                 $em->flush();
                return $this->redirect($this->generateUrl('ElemRefEstEnvoye'));
            }
        }


        $elementRef = array();

        $semestres = $em->getRepository("AppBackOfficeBundle:ResultatElp")->getSemestres($annee, $etudiant, $em, $op);
       
        if(count($semestres) != 0){
                
                $semestreActuel = $semestres[0]->getElement();
            
                $modules = $em->getRepository("AppBackOfficeBundle:ResultatElp")->getModules( $etudiant, $em, $semestreActuel);
                
                foreach($modules as $module){

                    $elements = $em->getRepository("AppBackOfficeBundle:ResultatElp")->getElements( $etudiant, $em, $module->getElement());
                    
                    $elementRef = array();
                    foreach ($elements as $element) {

                        $etat = -1;

                        $note = $element[1];                        
                        //return new Response(var_dump($element->getElement()->getNote()));
                        if($note >= 10) {

                            $d = $em->getRepository("AppBackOfficeBundle:Demande")->getDemandes( $etudiant, $em, $element[0]->getElement()->getCode());

                            if(count($d) != 0){

                                $etat = $d[0]->getLastEtatDemande()->getEtat();

                                switch ($etat) {
                                    case 'en attente':
                                        $etat = 0;
                                        break;
                                    case 'traiter':
                                        $etat = 1;
                                        break;
                                    case 'rejeter':
                                        $etat = 2;
                                        break;
                                    case 'valide':
                                        $etat = 3;
                                        break;
                                }
                                
                            }
                            else $aucunElementDemande = false;

                            $elementRef[] = array("element"=>$element[0]->getElement(),"etat"=>$etat);

                        }
                    }
                    if(count($elementRef > 0))// si le module contient des elements a refaire
                        $moduleElements[] = array($module->getElement(),$elementRef);
                }
            }
            //return new Response(var_dump(($elementRef[0]["etat"])));
            //return new Response(count($moduleElements[0][1]));
        return $this->render(
                                'AppFrontOfficeBundle:ElemRef:ElemRefEtu.html.twig', 
                                array('modules'             => $moduleElements,
                                      'err'                 => $err,
                                      'aucunElementDemande' => $aucunElementDemande,
                                      
                                    )
                            );
    }

    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();
       
        $entity = $em->getRepository('AppBackOfficeBundle:Demande')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find reclamation entity.');
        }
        $elem = $this->getDoctrine()->getRepository('AppBackOfficeBundle:ElementPedagogi');

        $donnees = $entity->getDonnees();
        
        $d = "";
        //return new Response(var_dump($donnees));
        foreach ($donnees as $donnee) {
            $element = $elem->findOneByCode($donnee);
            $d.= $element->getLib().",   ";
        }
        $entity->setDonnees($d);
        //$deleteForm = $this->createDeleteForm($id);
        //return new Response(var_dump($entity->getStatus()));
            
        return $this->render('AppFrontOfficeBundle:ElemRef:ConsulterElemRef.html.twig', array(
            'demande'      => $entity,
            //'delete_form' => $deleteForm->createView(), 
            
                   ));
    }


   
  public function estEnvoyeAction()
    {
        return $this->render('AppFrontOfficeBundle:ElemRef:estEnvoyer.html.twig');
                   
    }  
    
}

