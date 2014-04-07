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

        $session = 1;//$this->container->get("esconfig_manager")->getCurrentSemester();

        $annee = 2013-1;//$this->container->get("esconfig_manager")->getCurrentAcademicYear()-1;

        $aucunElementDemande = true;

        $op = array();

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
                //$entity->setReponse($reponse);
                
                
                $em->persist($entity);
                $em->flush();
                $etatDemandes =new EtatDemande();
                 $etatDemandes->setEtat("en attente");
                 $etatDemandes->setDemande($entity);
                 $em->persist($etatDemandes);
                 $em->flush();
                return $this->redirect($this->generateUrl('notification-etudiant'));
            }
        }


        $elementRef = array();

        $semestres = $em->getRepository("AppBackOfficeBundle:ResultatElp")->getSemestres($annee, $etudiant, $em, $op);
       
        if(count($semestres) != 0){
                
                $semestreActuel = $semestres[0]->getElement();
            
                $modules = $em->getRepository("AppBackOfficeBundle:ResultatElp")->getModules( $etudiant, $em, $semestreActuel);
                
                foreach($modules as $module){

                    $elements = $em->getRepository("AppBackOfficeBundle:ResultatElp")->getElements( $etudiant, $em, $module->getElement());
                    //return new Response(gettype($elements[1][1]));
                    foreach ($elements as $element) {

                        $etat = -1;

                        $note = $element[1];                        
                        
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


                            break;
                        }
                    }
                }
            }
            //return new Response(var_dump(($elementRef[0]["etat"])));
            //return new Response(gettype($elementRef[2]["etat"]));
        return $this->render(
                                'AppFrontOfficeBundle:ElemRef:ElemRefEtu.html.twig', 
                                array('elements'            => $elementRef,
                                      'err'                 => $err,
                                      'aucunElementDemande' => $aucunElementDemande,
                                      //'ex'=>9
                                    )
                            );
    }



   
    
    
}

