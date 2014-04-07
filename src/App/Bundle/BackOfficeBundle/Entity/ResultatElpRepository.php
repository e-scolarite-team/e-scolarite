<?php

namespace App\Bundle\BackOfficeBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * ResultatElpRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ResultatElpRepository extends EntityRepository
{
	public function deleteAll(){
		$this->getEntityManager()->createQuery("delete from AppBackOfficeBundle:ResultatElp")->execute();
	}

	public function getModules( $etudiant, $em, $parent){
		
		$qb = $em->createQueryBuilder();
                $qb->select('r', 'e')
                ->from('App\Bundle\BackOfficeBundle\Entity\ResultatElp', 'r')
                ->join('r.element', 'e')
                ->Where($qb->expr()->eq('r.status', '?1'))
                ->andWhere($qb->expr()->eq('r.etudiant', '?2'))                
                ->andWhere($qb->expr()->isNotNull('r.note'))                
                ->andWhere($qb->expr()->eq('e.parent', '?3'))
                ->setParameter(1, "NV")
                ->setParameter(2, $etudiant)                
                ->setParameter(3, $parent);                  
                return $qb->getQuery()->getResult();

	}
        public function getSemestres( $annee, $etudiant, $em, $op){
                
                $qb = $em->createQueryBuilder();
                $qb->select('r')
                ->from('App\Bundle\BackOfficeBundle\Entity\ResultatElp', 'r')
                ->join('r.element', 'e')                
                ->Where($qb->expr()->eq('r.etudiant', '?2'))
                ->andWhere($qb->expr()->eq('r.annee', '?4'))                
                ->andWhere(("e.nature like 'SM".$op[0]."' or e.nature like 'SM".$op[1]."' or e.nature like 'SM".$op[2]."' "))                
                ->setParameter(2, $etudiant)                
                ->setParameter(4, $annee)
                ->groupBy('e.code');  
                return $qb->getQuery()->getResult();

        }

        public function getElements( $etudiant, $em, $parent){
                
                $qb = $em->createQueryBuilder();
                $qb->select('r','max(r.note)')
                ->from('App\Bundle\BackOfficeBundle\Entity\ResultatElp', 'r')
                ->join('r.element', 'e')
                ->Where($qb->expr()->eq('r.etudiant', '?2'))                
                ->andWhere($qb->expr()->isNotNull('r.note'))
                ->andWhere($qb->expr()->eq('e.parent', '?3'))
                ->setParameter(2, $etudiant)
                ->setParameter(3, $parent)
                ->groupBy('e.code');  
                return $qb->getQuery()->getResult();
        }
//////////cette fonction retourne un objet permettant de savoir le semestre max contenu dans la base de données
        //pour  un étudiant donné          
          public function tousResultatEtudiant($id){
            
            $qb = $this->_em->createQuery
                    (
                    'SELECT DISTINCT r,etud,elt FROM AppBackOfficeBundle:ResultatElp r '
                    . 'JOIN r.etudiant etud '
                    . 'JOIN r.element elt '
                    . 'WHERE etud.id=:id '
                    . 'AND r.status is not null '
                    . "AND elt.nature LIKE 'SM%' "
                    . 'HAVING r.annee =(SELECT max(res.annee)FROM AppBackOfficeBundle:ResultatElp res '
                                          . 'JOIN res.etudiant etu '
                                          . 'JOIN res.element eltm '
                                          . 'WHERE etu.id=:id '
                                          . 'AND res.status is not null '
                                          . "AND eltm.nature LIKE 'SM%' "
                                          .') '
                    . 'AND elt.code =(SELECT max(el.code)FROM AppBackOfficeBundle:ResultatElp re '
                                          . 'JOIN re.etudiant etudi '
                                          . 'JOIN re.element el '
                                          . 'WHERE etudi.id=:id '
                                          . 'AND re.status is not null '
                                          . "AND el.nature LIKE 'SM%' "
                                          .')'
                    )
                    ->setParameter('id',$id);
              $resultat =$qb->getSingleResult();
            
            return $resultat ;
                 
        }
        
   // ModuleDeSemestre permet de determiner les modules Validé et Non Validé pour un étudiant      
      public function ModuleDeSemestreEtud($semestre,$id){//module d'1 semestre pour 1 etudiant avec toutes les info
               
          $qb = $this->_em->createQuery
                          (
                    'SELECT DISTINCT resul,elt FROM AppBackOfficeBundle:ResultatElp resul '
                  . 'JOIN resul.etudiant etud '
                  . 'jOIN resul.element elt '
                  . 'WHERE etud.id=:id '
                  . "AND resul.status in ('V','NV') "
                  . 'AND elt.parent=:sem '
                      )
                  ->setParameter('id', $id)
                  ->setParameter('sem', $semestre)
                       ;
               return $qb->getResult() ;
                    
        }
        
        public function ModuleDeSemestre($semestre){ //determiner les modules d'un semestre quelquonque
             $qb = $this->_em->createQuery
                          (
                    'SELECT DISTINCT elp FROM AppBackOfficeBundle:ElementPedagogi elp '
                    . 'JOIN elp.parent pa   '
                    . 'WHERE pa.code =:par '
                    . "AND elp.nature LIKE 'MOD%' "
                    )
                     ->setParameter('par', $semestre)
                            ;
             return $qb->getResult();
            
        }
        
         public function getAllValideModulesforEtudiant ($etudiant){
            $em = $this->getEntityManager();
            $query = $em->createQueryBuilder();
            $query->select('r', 'e')
            ->from('App\Bundle\BackOfficeBundle\Entity\ResultatElp', 'r')
            ->leftJoin('r.element', 'e')
            ->Where($query->expr()->eq('r.status', '?1'))
            ->andWhere($query->expr()->eq('r.etudiant', '?2'))
            ->andWhere($query->expr()->eq('e.nature', '?3'))
            ->setParameter(1, "V")
            ->setParameter(2, $etudiant)
            ->setParameter(3, "MOD");  
            $tous_les_modules_valider = $query->getQuery()->getResult();
            return $tous_les_modules_valider;
        }
        
        
        public function getCountPrerequisiteModules($etudiant, $M1, $M2){
            $em = $this->getEntityManager();
            $query = $em->createQueryBuilder();
            $query->select('r', 'e')
            ->from('App\Bundle\BackOfficeBundle\Entity\ResultatElp', 'r')
            ->leftJoin('r.element', 'e')
            ->Where($query->expr()->like('e.code', '?4'))
            ->orWhere($query->expr()->like('e.code', '?5'))
            ->andWhere($query->expr()->eq('r.status', '?1'))
            ->andWhere($query->expr()->eq('r.etudiant', '?2'))
            ->andWhere($query->expr()->eq('e.nature', '?3'))
            ->setParameter(1, "V")
            ->setParameter(2, $etudiant)
            ->setParameter(3, "MOD")
            ->setParameter(4, $M1)
            ->setParameter(5, $M2);  
            $CountPrerequisiteModules = $query->getQuery()->getResult();    
            return $CountPrerequisiteModules;
        }
        
        
        public function getMaxYearForEtudiant($etudiant){
            $em = $this->getEntityManager();
            $query = $em->createQueryBuilder();
            $query->select('r')
            ->addSelect($query->expr()->max('r.annee'))
            ->from('App\Bundle\BackOfficeBundle\Entity\ResultatElp', 'r')
            ->andWhere($query->expr()->eq('r.etudiant', '?2'))
            ->setParameter(2, $etudiant);
            $max = $query->getQuery()->getResult();
            $maxYear = (int) $max[0][1];
            return $maxYear;
        }
        
        
        public function getNVModulesInLastYearforEtudiant($etudiant, $year, $SxM1, $SxM2, $SxM3, $SxM4, $SyM1, $SyM2, $SyM3, $SyM4){
               $em = $this->getEntityManager();
               $query = $em->createQueryBuilder();
               $query->select('r', 'e')
               ->from('App\Bundle\BackOfficeBundle\Entity\ResultatElp', 'r')
               ->leftJoin('r.element', 'e')
               ->Where($query->expr()->like('e.code', '?4'))
               ->orWhere($query->expr()->like('e.code', '?5'))
               ->orWhere($query->expr()->like('e.code', '?6'))
               ->orWhere($query->expr()->like('e.code', '?7'))
               ->orWhere($query->expr()->like('e.code', '?8'))
               ->orWhere($query->expr()->like('e.code', '?9'))
               ->orWhere($query->expr()->like('e.code', '?10'))
               ->orWhere($query->expr()->like('e.code', '?11'))
               ->andWhere($query->expr()->eq('r.status', '?1'))
               ->andWhere($query->expr()->eq('r.etudiant', '?2'))
               ->andWhere($query->expr()->eq('e.nature', '?3'))
               ->andWhere($query->expr()->eq('r.annee', '?12'))
               ->setParameter(1, "NV")
               ->setParameter(2, $etudiant)
               ->setParameter(3, "MOD")
               ->setParameter(4, $SxM1)
               ->setParameter(5, $SxM2)
               ->setParameter(6, $SxM3)
               ->setParameter(7, $SxM4)
               ->setParameter(8, $SyM1)
               ->setParameter(9, $SyM2)
               ->setParameter(10, $SyM3)
               ->setParameter(11, $SyM4)
               ->setParameter(12, $year);
               $NVModules = $query->getQuery()->getResult();
               return $NVModules;
        }
        
         public function getVModulesforEtudiant($etudiant, $SxM1, $SxM2, $SxM3, $SxM4, $SyM1, $SyM2, $SyM3, $SyM4){
               $em = $this->getEntityManager();
               $query = $em->createQueryBuilder();
               $query->select('r', 'e')
               ->from('App\Bundle\BackOfficeBundle\Entity\ResultatElp', 'r')
               ->leftJoin('r.element', 'e')
               ->Where($query->expr()->like('e.code', '?4'))
               ->orWhere($query->expr()->like('e.code', '?5'))
               ->orWhere($query->expr()->like('e.code', '?6'))
               ->orWhere($query->expr()->like('e.code', '?7'))
               ->orWhere($query->expr()->like('e.code', '?8'))
               ->orWhere($query->expr()->like('e.code', '?9'))
               ->orWhere($query->expr()->like('e.code', '?10'))
               ->orWhere($query->expr()->like('e.code', '?11'))
               ->andWhere($query->expr()->eq('r.status', '?1'))
               ->andWhere($query->expr()->eq('r.etudiant', '?2'))
               ->andWhere($query->expr()->eq('e.nature', '?3'))
               ->setParameter(1, "V")
               ->setParameter(2, $etudiant)
               ->setParameter(3, "MOD")
               ->setParameter(4, $SxM1)
               ->setParameter(5, $SxM2)
               ->setParameter(6, $SxM3)
               ->setParameter(7, $SxM4)
               ->setParameter(8, $SyM1)
               ->setParameter(9, $SyM2)
               ->setParameter(10, $SyM3)
               ->setParameter(11, $SyM4);
               $VModules = $query->getQuery()->getResult();
               return $VModules;
        }
        
        public function getPositionOf($pos, $etudiant, $SxM1, $SxM2, $SxM3, $SxM4, $SyM1, $SyM2, $SyM3, $SyM4){
               $em = $this->getEntityManager();
               $query = $em->createQueryBuilder();
               $query->select('r', 'e')
               ->addSelect("MAX(LOCATE('$pos', e.code))")
               ->from('App\Bundle\BackOfficeBundle\Entity\ResultatElp', 'r')
               ->leftJoin('r.element', 'e')
               ->Where($query->expr()->like('e.code', '?4'))
               ->orWhere($query->expr()->like('e.code', '?5'))
               ->orWhere($query->expr()->like('e.code', '?6'))
               ->orWhere($query->expr()->like('e.code', '?7'))
               ->orWhere($query->expr()->like('e.code', '?8'))
               ->orWhere($query->expr()->like('e.code', '?9'))
               ->orWhere($query->expr()->like('e.code', '?10'))
               ->orWhere($query->expr()->like('e.code', '?11'))
               ->andWhere($query->expr()->eq('r.status', '?1'))
               ->andWhere($query->expr()->eq('r.etudiant', '?2'))
               ->andWhere($query->expr()->eq('e.nature', '?3'))
               ->setParameter(1, "V")
               ->setParameter(2, $etudiant)
               ->setParameter(3, "MOD")
               ->setParameter(4, $SxM1)
               ->setParameter(5, $SxM2)
               ->setParameter(6, $SxM3)
               ->setParameter(7, $SxM4)
               ->setParameter(8, $SyM1)
               ->setParameter(9, $SyM2)
               ->setParameter(10, $SyM3)
               ->setParameter(11, $SyM4);
               $positionof = $query->getQuery()->getResult();
               $pos = (int) $positionof[0][1];
               return $pos;
        }
        
        public function getFiliere($pos, $etudiant){
              $em = $this->getEntityManager();
               $Qr = $em->createQueryBuilder();
               $Qr->select('r', 'e')
               ->addSelect("SUBSTRING(e.code, 1, $pos)")
               ->from('App\Bundle\BackOfficeBundle\Entity\ResultatElp', 'r')
               ->leftJoin('r.element', 'e')
               ->Where($Qr->expr()->eq('r.etudiant', '?2'))
               ->andWhere($Qr->expr()->eq('e.nature', '?3'))
               ->setParameter(2, $etudiant)
               ->setParameter(3, "MOD");
               $FLR = $Qr->getQuery()->getResult();
               $flr = $FLR[0][1];
               $filiere = $flr . '%';
               return $filiere;
        }
        
        public function getVSemestreNumber($etudiant){
            $em = $this->getEntityManager();
            $query = $em->createQueryBuilder();
            $query->select('r', 'e')
            ->from('App\Bundle\BackOfficeBundle\Entity\ResultatElp', 'r')
            ->leftJoin('r.element', 'e')
            ->Where($query->expr()->eq('r.etudiant', '?2'))
            ->andWhere($query->expr()->in('e.nature', '?3')) 
            ->andWhere($query->expr()->eq('r.status', '?1'))
            ->setParameter(1, "V")
            ->setParameter(2, $etudiant)
            ->setParameter(3, array ( "SM01" , "SM02", "SM03", "SM04"));  
            $VSemestre = $query->getQuery()->getResult();
            return count($VSemestre);
        }
}
