<?php

namespace App\Bundle\BackOfficeBundle\Controller;

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


class DemandeController extends Controller {
  
	
  
    
    // --------------------------------------------------------------------
   
    public function listedemandeAction() {
     
          $repDemande = $this->getDoctrine()->getRepository('AppBackOfficeBundle:Demande');
          
        $demandes = $repDemande->findAll();
        return $this->render(
                                'AppBackOfficeBundle:Demande:listedemande.html.twig', 
                                array('demandes' => $demandes)
                            );
    }
    
    
	
  
    
    
    public function traiterdemandeAction($id) {
         
          $repAdmin = $this->getDoctrine()->getRepository('AppBackOfficeBundle:Admin');
          $admin = $repAdmin->findOneById(1);
          $em = $this->getDoctrine()->getEntityManager();
          $repDemande = $this->getDoctrine()->getRepository('AppBackOfficeBundle:Demande');
          $demande = $repDemande->findOneById($id);
          
          if($this->get('request')->request->get('rejeter') == 'rejeter'){
                $qb = $em->createQueryBuilder();
                $qb->update('App\Bundle\BackOfficeBundle\Entity\Demande', 'd')
                ->set('d.status', '?1')
                ->set('d.updatedAt', '?3')
                ->where($qb->expr()->eq('d.id', '?2'))
                ->setParameter(1, 1)
                ->setParameter(2, $id)
                ->setParameter(3, new \DateTime());
                $test = $qb->getQuery()->getResult();

                $qq = $em->createQueryBuilder();
                $qq->update('App\Bundle\BackOfficeBundle\Entity\EtatDemande', 'e')
                ->set('e.etat', '?1')
                ->set('e.admin', '?2')
                ->where($qq->expr()->eq('e.demande', '?3'))
                ->setParameter(1, 'Rejeter')
                ->setParameter(2, 1)//id admin
                ->setParameter(3, $demande);

                $test = $qq->getQuery()->getResult();
                
                return $this->redirect($this->get('router')->generate('listedemande', array()));
                
         } elseif ($this->get('request')->request->get('fixer') == 'fixer'){
                $justification = $this->get('request')->request->get('justification');
                $s = $this->get('request')->request->get('rv');
                /*03/25/2014
                2015-08-30*/
                $rv = \DateTime::createFromFormat('d-m-Y', $s)->format('Y-m-d');
               // $rv = substr($s, 6, 4) . "-" . substr($s, 0, 2) . "-" . substr($s, 3, 2);
                $qb = $em->createQueryBuilder();
                
                $qb->update('App\Bundle\BackOfficeBundle\Entity\Demande', 'd')
                ->set('d.status', '?1')
                ->set('d.updatedAt', '?3')
                ->set('d.dateReponce', '?4')
                ->where($qb->expr()->eq('d.id', '?2'))
                ->setParameter(1, 1)
                ->setParameter(2, $id)
                ->setParameter(3, new \DateTime())
                ->setParameter(4, new \DateTime($rv));
                $test = $qb->getQuery()->getResult();
                
                
              
                $qq = $em->createQueryBuilder();
                $qq->update('App\Bundle\BackOfficeBundle\Entity\EtatDemande', 'e')
                ->set('e.etat', '?1')
                ->set('e.admin', '?2')
                ->set('e.justification', '?4')
                ->where($qq->expr()->eq('e.demande', '?3'))
                ->setParameter(1, 'Traiter')
                ->setParameter(2, 1)// id admin
                ->setParameter(3, $demande)
                ->setParameter(4, $justification);

                $test = $qq->getQuery()->getResult();
                
               return $this->redirect($this->get('router')->generate('listedemande', array()));            
         } 
        $repDemande = $this->getDoctrine()->getRepository('AppBackOfficeBundle:Demande');
        $demande = $repDemande->findOneById($id);
              
        return $this->render( 
                                'AppBackOfficeBundle:Demande:traiterdemande.html.twig', 
                                 array( 'demande' => $demande )
                            );
    }


}

