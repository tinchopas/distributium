<?php

namespace Distributium\BackendBundle\Entity;

use Doctrine\ORM\EntityRepository;

class ItemRepository extends EntityRepository
{
    public function findByCategory($category, $lodgingSizes, $lodgingCategories, $lodgingTypes, $regions)
    {
        //var_dump(array_sum($lodgingCategories));exit;
        //var_dump($lodgingTypes);exit;
	    $qb = $this->getEntityManager()->createQueryBuilder();

	    $qb->select('i.id, i.name, concat(\'/uploads/\', im.id, im.path) as image, c.name as companyName, i.shortDescription')
		    ->from('DistributiumBackendBundle:Item', 'i')
		    ->leftJoin('i.image', 'im')
            ->leftJoin('i.company', 'c')
            ->where('i.category = :category')
            ->andWhere('(:lodgingSizesMask = 0) OR (BIT_AND(i.lodgingSizeMask, :lodgingSizesMask) = :lodgingSizesMask )')
            ->andWhere('(:lodgingCategoriesMask = 0) OR (BIT_AND(i.lodgingCategoryMask, :lodgingCategoriesMask) = :lodgingCategoriesMask )')
            ->setParameter('category', $category)
            ->setParameter('lodgingSizesMask', bindec(array_sum($lodgingSizes)))
            ->setParameter('lodgingCategoriesMask', bindec(array_sum($lodgingCategories)))
            ;

        if (!empty($lodgingTypes)) {
            $qb->addSelect('lt.name as lodgingSize')
                ->innerJoin('i.lodgingTypes', 'lt', 'WITH', 'lt.id IN (:lodgingTypes)')
                ->setParameter('lodgingTypes', $lodgingTypes)
                ->setParameter('lodgingTypesCount', count($lodgingTypes))
                ->groupBy('i.id')
                ->having('count(i.id) = :lodgingTypesCount');

        }

	    $result = $qb->getQuery()->getResult();
	    //var_dump($qb->getQuery()->getSQL());exit;

        return $result;
    }
}
