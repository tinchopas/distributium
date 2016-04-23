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

	    $qb->select('i.id, i.name, concat(\'/uploads/\', l.id, l.path) as logo, c.name as companyName, i.shortDescription')
		    ->from('DistributiumBackendBundle:Item', 'i')
		    ->leftJoin('i.logo', 'l')
            ->leftJoin('i.company', 'c')
            ->where('i.category = :category')
            ->andWhere('(:lodgingSizesMask = 0) OR (BIT_AND(i.lodgingSizeMask, :lodgingSizesMask) = :lodgingSizesMask )')
            ->andWhere('(:lodgingCategoriesMask = 0) OR (BIT_AND(i.lodgingCategoryMask, :lodgingCategoriesMask) = :lodgingCategoriesMask )')
            ->setParameter('category', $category)
            ->setParameter('lodgingSizesMask', bindec(array_sum($lodgingSizes)))
            ->setParameter('lodgingCategoriesMask', bindec(array_sum($lodgingCategories)))
            ;

        if (!empty($lodgingTypes)) {
            $qb->addSelect('lt.name as lodgingType')
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

    public function searchQuizResults1($lodgingSizes, $lodgingCategories, $lodgingTypes, $regions)
    {
        //var_dump(array_sum($lodgingCategories));exit;
        //var_dump($lodgingTypes);exit;
	    $qb = $this->getEntityManager()->createQueryBuilder();

	    $qb->select('i')
		    ->from('DistributiumBackendBundle:Item', 'i')
            ->andWhere('(:lodgingSizesMask = 0) OR (BIT_AND(i.lodgingSizeMask, :lodgingSizesMask) = :lodgingSizesMask )')
            ->andWhere('(:lodgingCategoriesMask = 0) OR (BIT_AND(i.lodgingCategoryMask, :lodgingCategoriesMask) = :lodgingCategoriesMask )')
            ->setParameter('lodgingSizesMask', bindec(array_sum($lodgingSizes)))
            ->setParameter('lodgingCategoriesMask', bindec(array_sum($lodgingCategories)))
            ;

        if (!empty($lodgingTypes)) {
            //$qb->addSelect('lt.name as lodgingType')
                $qb->innerJoin('i.lodgingTypes', 'lt', 'WITH', 'lt.id IN (:lodgingTypes)')
                ->setParameter('lodgingTypes', $lodgingTypes)
                ->setParameter('lodgingTypesCount', count($lodgingTypes))
                ->groupBy('i.id')
                ->having('count(i.id) = :lodgingTypesCount')
                ;

        }

	    $result = $qb->getQuery()->getResult();
	    //ldd($result);
	    //var_dump($qb->getQuery()->getSQL());exit;

        return $result;
    }

    public function searchQuizResults($lodgingSizes, $lodgingCategories, $lodgingTypes, $regions)
    {
        //var_dump(array_sum($lodgingCategories));exit;
        //var_dump($lodgingTypes);exit;
	    $qb = $this->getEntityManager()->createQueryBuilder();

        $qb->select('pms.name as pms_name, cm.name as cm_name, ibe.name as ibe_name, rms.name as rms_name')

            ->from('DistributiumBackendBundle:Item', 'pms')
            ->from('DistributiumBackendBundle:Item', 'cm')
            ->from('DistributiumBackendBundle:Item', 'ibe')
            ->from('DistributiumBackendBundle:Item', 'rms')

            ->innerJoin('pms.ic', 'pms_ic')
            ->innerJoin('cm.ic', 'cm_ic')
            ->innerJoin('ibe.ic', 'ibe_ic')

            ->where('pms.category = 3')
            ->andWhere('cm.category = 1')
            ->andWhere('ibe.category = 2')
            ->andWhere('rms.category = 4')

            ->andWhere('
            (
                (pms_ic.cwi = cm.id) AND (cm_ic.cwi = ibe.id)  AND (ibe_ic.cwi = rms.id)
            )
            ')
/*
            ->andWhere('(:lodgingSizesMask = 0) OR (BIT_AND(pms.lodgingSizeMask, :lodgingSizesMask) = :lodgingSizesMask )')
            ->andWhere('(:lodgingSizesMask = 0) OR (BIT_AND(cm.lodgingSizeMask, :lodgingSizesMask) = :lodgingSizesMask )')
            ->andWhere('(:lodgingSizesMask = 0) OR (BIT_AND(ibe.lodgingSizeMask, :lodgingSizesMask) = :lodgingSizesMask )')
            ->andWhere('(:lodgingSizesMask = 0) OR (BIT_AND(rms.lodgingSizeMask, :lodgingSizesMask) = :lodgingSizesMask )')


            ->andWhere('(:lodgingCategoriesMask = 0) OR (BIT_AND(pms.lodgingCategoryMask, :lodgingCategoriesMask) = :lodgingCategoriesMask )')
            ->andWhere('(:lodgingCategoriesMask = 0) OR (BIT_AND(cm.lodgingCategoryMask, :lodgingCategoriesMask) = :lodgingCategoriesMask )')
            ->andWhere('(:lodgingCategoriesMask = 0) OR (BIT_AND(ibe.lodgingCategoryMask, :lodgingCategoriesMask) = :lodgingCategoriesMask )')
            ->andWhere('(:lodgingCategoriesMask = 0) OR (BIT_AND(rms.lodgingCategoryMask, :lodgingCategoriesMask) = :lodgingCategoriesMask )')

            ->setParameter('lodgingSizesMask', bindec(array_sum($lodgingSizes)))
            ->setParameter('lodgingCategoriesMask', bindec(array_sum($lodgingCategories)))
 */
            ;


	    $result = $qb->getQuery()->getResult();
	    //ldd($result);
	    //var_dump($qb->getQuery()->getSQL());exit;

        return $result;
    }

    /*
    public function searchQuizResults($params)
    {
        $qb = $this->getEntityManager()->createQueryBuilder();

	    $qb->select('i.id, i.name, c.id as category, ic.id as ici, cwi.id as cwii')
            ->from('DistributiumBackendBundle:Item', 'i')
            ->leftJoin('i.category', 'c')
            ->leftJoin('i.ic', 'ic')
            ->leftJoin('i.cwi', 'cwi')
            ->where('c.id IN (3,1,2,4)')
         //   ->groupBy('c.id')
            ;

	    $result = $qb->getQuery()->getResult();
        ldd($result);
        return $result;
    }
     */
}
