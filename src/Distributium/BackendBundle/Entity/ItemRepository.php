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

    public function searchQuizResults($params)
    {
        $lodgingSizes = $params['lodgingSize'];
        $lodgingCategories = $params['lodgingCategory'];
        $lodgingTypes = $params['lodgingType'];
        $regions = $params['lodgingRegion'];

	    $qb = $this->getEntityManager()->createQueryBuilder();

        $qb->select('
            pms.name as pms_name, cm.name as cm_name, ibe.name as ibe_name, rms.name as rms_name,
            pms.lodgingSizeMask as pms_lodgingSizeMask, cm.lodgingSizeMask as cm_lodgingSizeMask, ibe.lodgingSizeMask as ibe_lodgingSizeMask, rms.lodgingSizeMask as rms_lodgingSizeMask,
            pms.lodgingCategoryMask as pms_lodgingCategoryMask, cm.lodgingCategoryMask as cm_lodgingCategoryMask, ibe.lodgingCategoryMask as ibe_lodgingCategoryMask, rms.lodgingCategoryMask as rms_lodgingCategoryMask
            ')

            ->from('DistributiumBackendBundle:Item', 'pms')
            ->from('DistributiumBackendBundle:Item', 'cm')
            ->from('DistributiumBackendBundle:Item', 'ibe')
            ->from('DistributiumBackendBundle:Item', 'rms')

            ->innerJoin('pms.ic', 'pms_ic')
            ->innerJoin('cm.ic', 'cm_ic')
            ->innerJoin('rms.ic', 'rms_ic')
            ;

        if ($params['c_op_3'] == 1) {
            $qb->where('pms.id = ' . $params['c_op_val_3']);
        } elseif ($params['c_op_3'] == 2) {
            $qb->where('pms.category = 3 AND pms.id != '. $params['c_op_val_3']);
        } else {
            $qb->where('pms.category = 3');
        }

        if ($params['c_op_1'] == 1) {
            $qb->andWhere('cm.id = ' . $params['c_op_val_1']);
        } elseif ($params['c_op_1'] == 2) {
            $qb->andWhere('cm.category = 1 AND cm.id != '. $params['c_op_val_1']);
        } else {
            $qb->andWhere('cm.category = 1');
        }

        if ($params['c_op_2'] == 1) {
            $qb->andWhere('ibe.id = ' . $params['c_op_val_2']);
        } elseif ($params['c_op_2'] == 2) {
            $qb->andWhere('ibe.category = 2 AND ibe.id != '. $params['c_op_val_2']);
        } else {
            $qb->andWhere('ibe.category = 2');
        }

        if ($params['c_op_4'] == 1) {
            $qb->andWhere('rms.id = ' . $params['c_op_val_4']);
        } elseif ($params['c_op_4'] == 2) {
            $qb->andWhere('rms.category = 4 AND rms.id != '. $params['c_op_val_4']);
        } else {
            $qb->andWhere('rms.category = 4');
        }



        $qb->andWhere('
            (
                (rms_ic.cwi = pms.id) AND (pms_ic.cwi = cm.id) AND (cm_ic.cwi = ibe.id)
            )
            ')

            ->andWhere('(:lodgingSizesMask = 0 OR pms.lodgingSizeMask IS NULL) OR (BIT_AND(pms.lodgingSizeMask, :lodgingSizesMask) = :lodgingSizesMask )')
            ->andWhere('(:lodgingSizesMask = 0 OR pms.lodgingSizeMask IS NULL) OR (BIT_AND(cm.lodgingSizeMask, :lodgingSizesMask) = :lodgingSizesMask )')
            ->andWhere('(:lodgingSizesMask = 0 OR pms.lodgingSizeMask IS NULL) OR (BIT_AND(ibe.lodgingSizeMask, :lodgingSizesMask) = :lodgingSizesMask )')
            ->andWhere('(:lodgingSizesMask = 0 OR pms.lodgingSizeMask IS NULL) OR (BIT_AND(rms.lodgingSizeMask, :lodgingSizesMask) = :lodgingSizesMask )')

            ->andWhere('(:lodgingCategoriesMask = 0 OR pms.lodgingCategoryMask IS NULL) OR (BIT_AND(pms.lodgingCategoryMask, :lodgingCategoriesMask) = :lodgingCategoriesMask )')
            ->andWhere('(:lodgingCategoriesMask = 0 OR cm.lodgingCategoryMask IS NULL) OR (BIT_AND(cm.lodgingCategoryMask, :lodgingCategoriesMask) = :lodgingCategoriesMask )')
            ->andWhere('(:lodgingCategoriesMask = 0 OR ibe.lodgingCategoryMask IS NULL) OR (BIT_AND(ibe.lodgingCategoryMask, :lodgingCategoriesMask) = :lodgingCategoriesMask )')
            ->andWhere('(:lodgingCategoriesMask = 0 OR rms.lodgingCategoryMask IS NULL) OR (BIT_AND(rms.lodgingCategoryMask, :lodgingCategoriesMask) = :lodgingCategoriesMask )')

            ->setParameter('lodgingSizesMask', bindec(array_sum($lodgingSizes)))
            ->setParameter('lodgingCategoriesMask', bindec(array_sum($lodgingCategories)))
            ;

        /*
        if (!empty($lodgingTypes)) {
            //$qb->addSelect('lt.name as lodgingType')
            $qb->innerJoin('i.lodgingTypes', 'lt', 'WITH', 'lt.id IN (:lodgingTypes)')
                ->setParameter('lodgingTypes', $lodgingTypes)
                ->setParameter('lodgingTypesCount', count($lodgingTypes))
                ->groupBy('i.id')
                ->having('count(i.id) = :lodgingTypesCount')
                ;

        }
         */

            
        $result = $qb->getQuery()->getResult();
        ldd($params, $result);
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
