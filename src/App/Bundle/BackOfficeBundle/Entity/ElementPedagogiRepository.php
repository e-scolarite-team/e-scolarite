<?php

namespace App\Bundle\BackOfficeBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * ElementPedagogiRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ElementPedagogiRepository extends EntityRepository
{
	public function deleteAll(){
		$this->getEntityManager()->createQuery("delete from AppBackOfficeBundle:ElementPedagogi")->execute();
	}
}
