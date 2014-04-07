<?php

namespace App\Bundle\BackOfficeBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * DemandeRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class DemandeRepository extends EntityRepository
{
	public function getDemandes( $etudiant, $em, $elem ){
                
                $qb = $em->createQueryBuilder();
                $qb->select('d')
                ->from('App\Bundle\BackOfficeBundle\Entity\Demande', 'd')
                ->join('d.typeDemande', 't')
                ->Where($qb->expr()->eq('d.etudiant', '?1'))                                
                ->andWhere($qb->expr()->like('d.donnees', '?2'))
                ->andWhere($qb->expr()->eq('t.code', '?3'))                
                ->setParameter(1, $etudiant)
                ->setParameter(2, "%".$elem."%")               
                ->setParameter(3, "ER");                
                return $qb->getQuery()->getResult();

        }
	public function getLastReponceDate()
	{
		
		$qb = $this->createQueryBuilder("d")->
		select ("max(d.dateReponce)")->getQuery();
		$resutls=$qb->getResult();
		return $resutls[0][1];
	}
        
         public function getDemandess ($etudiant, $typedemande, $date_debut, $date_fin){

            $em = $this->getEntityManager();
            $query = $em->createQueryBuilder();
            $query->select('d')
            ->from('App\Bundle\BackOfficeBundle\Entity\Demande', 'd')
            ->where($query->expr()->eq('d.etudiant', '?1'))
            ->andWhere($query->expr()->eq('d.typeDemande', '?2'))
            ->andWhere($query->expr()->neq('d.status', '?5'))
            ->andWhere($query->expr()->between('d.createdAt', '?3', '?4'))
            ->setParameter(1, $etudiant)
            ->setParameter(2, $typedemande)
            ->setParameter(3, $date_debut)
            ->setParameter(4, $date_fin)
            ->setParameter(5, 2);
            $Demandes = $query->getQuery()->getResult();
            return $Demandes;

        }
	
}
