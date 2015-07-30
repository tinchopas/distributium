<?php

namespace Distributium\BackendBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;

class ItemAdmin extends Admin
{
    /**
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('name')
            ->add('description')
        ;
    }

    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('name')
            ->add('description')
            ->add('category')
            ->add('slug')
            ->add('_action', 'actions', array(
                'actions' => array(
                    'show' => array(),
                    'edit' => array(),
                    'delete' => array(),
                )
            ))
        ;
    }

    /**
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {


        $em =  $this->getConfigurationPool()->getContainer()->get('Doctrine')->getManager();
        $query = $em->createQueryBuilder('i')
            ->select('i')
            ->from('Distributium/BackendBundle/Entity/Item', 'i')
            ->where('i.id IS NOT NULL');
            //->orderBy('c.root, c.lft', 'ASC');
        $myConnectionOptions = array('required' => true,'query' => $query);

        $formMapper
            ->add('name', 'text', array('label' => 'Name'))
            ->add('description', 'text', array('label' => 'Description'))
            ->add('image', 'sonata_type_admin')
            ->add('category', 'entity', array('label' => 'Category', 'class' => 'Distributium\BackendBundle\Entity\Category'))
            ->add('myConnection', 'sonata_type_model', $myConnectionOptions)

        ;
    }

    /**
     * @param ShowMapper $showMapper
     */
    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->add('name')
            ->add('description')
        ;
    }
}
