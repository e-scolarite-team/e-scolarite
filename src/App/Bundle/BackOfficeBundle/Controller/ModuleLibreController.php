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

class ModulelibreController extends Controller
{
     public function listemodulelibreAction() {

        $admin = $this->getUser();
        $em = $this->getDoctrine()->getEntityManager();

        $td = $em->createQueryBuilder();
        $td->select('t')
        ->from('App\Bundle\BackOfficeBundle\Entity\TypeDemande', 't')
        ->Where($td->expr()->in('t.code', '?1'))
        ->setParameter(1, '5M');
        $typedemandes = $td->getQuery()->getResult();
        
        $qb = $em->createQueryBuilder();
        $qb->select('d')
        ->from('App\Bundle\BackOfficeBundle\Entity\Demande', 'd')
        ->Where($qb->expr()->eq('d.status', '?1'))
        ->andWhere($qb->expr()->eq('d.typeDemande', '?2'))
        ->setParameter(1, 0)
        ->setParameter(2, $typedemandes);
        $demandes = $qb->getQuery()->getResult();
        
        return $this->render(
            'AppBackOfficeBundle:Modulelibre:listemodulelibre.html.twig', 
            array('demandes' => $demandes)
            );
    }
    
    

    
    public function traitermodulelibreAction($id) {
          $em = $this->getDoctrine()->getEntityManager();
          $admin = $this->getUser();
          
          $repDemande = $this->getDoctrine()->getRepository('AppBackOfficeBundle:Demande');
          $demande = $repDemande->findOneById($id);
          
          if($this->get('request')->request->get('rejeter') == 'rejeter'){
              
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
                
                return $this->redirect($this->get('router')->generate('listemodulelibre', array()));
                
                
         } elseif ($this->get('request')->request->get('fixer') == 'fixer'){
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
                
               return $this->redirect($this->get('router')->generate('listemodulelibre', array()));            
         } 
              
        return $this->render( 
                                'AppBackOfficeBundle:Modulelibre:traitermodulelibre.html.twig', 
                                 array( 'demande' => $demande )
                            );
    }

}
