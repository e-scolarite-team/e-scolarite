<?php

namespace App\Bundle\BackOfficeBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use App\Bundle\BackOfficeBundle\Entity\Demande;
use App\Bundle\BackOfficeBundle\Entity\TypeDemande;
use App\Bundle\BackOfficeBundle\Entity\Etudiant;
use App\Bundle\BackOfficeBundle\Entity\EtatDemande;
use App\Bundle\BackOfficeBundle\Entity\Admin;
use App\Bundle\BackOfficeBundle\Entity\ElementPedagogi;
use App\Bundle\BackOfficeBundle\Form\Data\ImportData;
use App\Bundle\BackOfficeBundle\Form\ImportFormType;

class ElementRefaireController extends Controller
{

    
    public function listeelementrefaireAction()
    {
        $em = $this->getDoctrine()->getManager();

       $admin = $this->getUser();
            
            $repTypeDemande = $this->getDoctrine()->getRepository('AppBackOfficeBundle:TypeDemande');
            $typedemande = $repTypeDemande->findOneByCode('ER');
            
             $repDemande = $this->getDoctrine()->getRepository('AppBackOfficeBundle:Demande');
             $demande = $repDemande->findByTypeDemande($typedemande);
                        // var_dump($demande);
              $elem = $this->getDoctrine()->getRepository('AppBackOfficeBundle:ElementPedagogi');
             
            for($i=0;$i<count($demande);$i++){
              $element = $elem->findOneByCode($demande[$i]->getDonnees()['0']);
              $demande[$i]->setDonnees($element->getLib());
            }
             

            /* $elem = $this->getDoctrine()->getRepository('AppBackOfficeBundle:ElementPedagogi');
             
             for($i=0;$i<count($demande);$i++){
              $element = $elem->findOneByCode($demande->getDonnees()['0']);
              $demande->setDonnees($element->getLib());
             }*/
        $qb = $em->createQueryBuilder();
            $qb->select('e')
            ->from('App\Bundle\BackOfficeBundle\Entity\EtatDemande', 'e')
            ->where($qb->expr()->in('e.demande', '?1'))
            ->setParameter(1, $demande);
            $etatdemandes = $qb->getQuery()->getResult();

            return $this->render(
                                'AppBackOfficeBundle:Elementarefaire:listedemanderefaire.html.twig', 
                                array('etatdemandes' => $etatdemandes)
                            );

        
       
    }
public function traiterelementrefaireAction($id) {
          $em = $this->getDoctrine()->getEntityManager();
          $admin = $this->getUser();
          
          $repDemande = $this->getDoctrine()->getRepository('AppBackOfficeBundle:Demande');
          $demande = $repDemande->findOneById($id);
          
          $elem = $this->getDoctrine()->getRepository('AppBackOfficeBundle:ElementPedagogi');
          $donnees = $demande->getDonnees();
        
          $d = "";
          //return new Response(var_dump($donnees));
          foreach ($donnees as $donnee) {
              $element = $elem->findOneByCode($donnee);
              $d.= $element->getLib().",   ";
          }
          $demande->setDonnees($d);
            
              
            
            //var_dump($demande);
          if($this->get('request')->request->get('rejeter') == 'rejeter'){
              // return new Response("Rejeter");
              
                $qb = $em->createQueryBuilder();
                $qb->update('App\Bundle\BackOfficeBundle\Entity\Demande', 'd')
                ->set('d.status', '?1')
                ->set('d.updatedAt', '?3')
                ->set('d.notified', '?4')
                ->where($qb->expr()->eq('d.id', '?2'))
                ->setParameter(1, 2)
                ->setParameter(2, $id)
                ->setParameter(4, 0)
                ->setParameter(3, new \DateTime());
                $test = $qb->getQuery()->getResult();

                $qq = $em->createQueryBuilder();
                $qq->update('App\Bundle\BackOfficeBundle\Entity\EtatDemande', 'e')
                ->set('e.etat', '?1')
                ->set('e.admin', '?2')
                ->where($qq->expr()->eq('e.demande', '?3'))
                ->setParameter(1, 'Rejeter')
                ->setParameter(2, $admin)
                ->setParameter(3, $demande);

                $test = $qq->getQuery()->getResult();
                
                return $this->redirect($this->get('router')->generate('listeelementrefaire', array()));
                
                
         } elseif ($this->get('request')->request->get('valider') == 'valider'){
                 //return new Response("Valider");
                $qb = $em->createQueryBuilder();
                
                $qb->update('App\Bundle\BackOfficeBundle\Entity\Demande', 'd')
                ->set('d.status', '?1')
                ->set('d.updatedAt', '?3')
                ->set('d.notified', '?4')
                ->where($qb->expr()->eq('d.id', '?2'))
                ->setParameter(1, 1)
                ->setParameter(2, $id)
                ->setParameter(4, 0)
                ->setParameter(3, new \DateTime());
                $test = $qb->getQuery()->getResult();
                
                
              
                $qq = $em->createQueryBuilder();
                $qq->update('App\Bundle\BackOfficeBundle\Entity\EtatDemande', 'e')
                ->set('e.etat', '?1')
                ->set('e.admin', '?2')
                ->where($qq->expr()->eq('e.demande', '?3'))
                ->setParameter(1, 'Valider')
                ->setParameter(2, $admin)
                ->setParameter(3, $demande);

                $test = $qq->getQuery()->getResult();
                
               return $this->redirect($this->get('router')->generate('listeelementrefaire', array()));            
         } 
              
        return $this->render( 
                                'AppBackOfficeBundle:Elementarefaire:traiterdemanderefaire.html.twig', 
                                 array( 'demande' => $demande )
                            );
    }

   }
