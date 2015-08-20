<?php

namespace Distributium\BackendBundle\Entity;

use Doctrine\ORM\EntityRepository;

class ItemConnectionRepository extends EntityRepository
{
    public function findConnectedItemsByCategory($id)
    {
        $result = $this->getEntityManager()
            ->createQuery(
                'SELECT i FROM DistributiumBackendBundle:Item i
                     INNER JOIN DistributiumBackendBundle:ItemConnection ic ON (ic.item = i.id)'
            )
            ->getResult();

        ldd($result);
    }
}
