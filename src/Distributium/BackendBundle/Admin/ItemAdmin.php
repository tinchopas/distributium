<?php

namespace Distributium\BackendBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class ItemAdmin extends Admin
{
    private $categories;
    private $ff;
    private $formMapper;

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

    public function preSetDataEvent(FormEvent $event) {
        $subject = $event->getData();
        $myCategory = null;
        $id = null;
        if ( $this->getSubject() !== null && $this->getSubject()->getCategory() !== null) {
            $myCategory = $this->getSubject()->getCategory();
            $id = $this->getSubject()->getId();
        }

        $em = $this->modelManager->getEntityManager('DistributiumBackendBundle:Category');
        $query = $em->createQueryBuilder('c')
            ->select('c')
            ->from('DistributiumBackendBundle:Category', 'c')
            ->where('c.id IS NOT NULL');

        $this->categories = $query->getQuery()->getResult();

        if ($myCategory != null) {
	    foreach ($this->categories as $category) {

                if($myCategory->getId() == $category->getId()) {
                    continue;
                }
                $query = $em->createQueryBuilder('i')
                    ->select('i')
                    ->from('DistributiumBackendBundle:Item', 'i')
                    ->where('i.id IS NOT NULL')
                    ->andWhere('i.category = :category')
                    ->andWhere('i.id != :id');
                $query->setParameter('category',$category);
                $query->setParameter('id',$id);

                $queryData = $em->createQueryBuilder('i')
                    ->select('i')
                    ->from('DistributiumBackendBundle:Item', 'i')
                    ->leftJoin('i.ic', 'ic1')
                    ->leftJoin('i.cwi', 'ic2')
                    ->where('i.category = :category')
                    ->andWhere('ic1.cwi = :id or ic2.item = :id');
                $queryData->setParameter('category', $category);
                $queryData->setParameter('id', $id);

	        $options = array(
                    'mapped'=>false,
                    'auto_initialize' => false,
                    'required' => false,
                    'query_builder' => $query,
                    'class' => 'Distributium\BackendBundle\Entity\Item',
                    'multiple'=>true,
                    'data' => $queryData->getQuery()->getResult(),
                    'label'=> $category->getName()
                );
                $event->getForm()->add($this->ff->createNamed($category->getIdentifier(), 'entity', null, $options));
	    }
        }
    }

    public function preSubmitEvent(FormEvent $event) {
        $subject = $event->getData();

        $myConnections = array();
        foreach ($this->categories as $category) {
            if ($category->getId() == $subject['category'] ||
                !array_key_exists($category->getIdentifier(), $subject)) {
        
                continue;
            }
            
            $myConnections = array_merge($myConnections, $subject[$category->getIdentifier()]);
        }

        $subject['myConnection'] = array_unique($myConnections);

        $event->setData($subject);
    }

    /**
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $admin = $this;
        $builder = $formMapper->getFormBuilder();
        $this->ff = $builder->getFormFactory();

        $formMapper->getFormBuilder()->addEventListener(FormEvents::POST_SET_DATA, array($this, 'preSetDataEvent'));
        $formMapper->getFormBuilder()->addEventListener(FormEvents::PRE_SUBMIT, array($this, 'preSubmitEvent'));


        $em = $this->modelManager->getEntityManager('DistributiumBackendBundle:Item');

	$connectionOptions = array(
            'required' => false, 
            'class' => 'Distributium\BackendBundle\Entity\Item', 
            'multiple'=>true, 'btn_add'=>false,
            'attr'=>array("style" => "display:none;"),
            'label' => false
        );

        $formMapper
		->add('name', 'text', array('label' => 'Name'))
		->add('image', 'sonata_type_admin', array('required' => false))
                ->add('company', 'entity', array('label' => 'Company', 'class' => 'Distributium\BackendBundle\Entity\Company', 'required' => false))
                ->add('category', 'entity', array('label' => 'Category', 'class' => 'Distributium\BackendBundle\Entity\Category', 'required' => false))
                ->add('myConnection', 'sonata_type_model', $connectionOptions)
		->add('description', 'sonata_formatter_type', array(
					'source_field'         => 'rawDescription',
					'source_field_options' => array('attr' => array('class' => 'span6', 'rows' => 10)),
					'format_field'         => 'descriptionFormatter',
					'target_field'         => 'description',
					'ckeditor_context'     => 'default',
					'event_dispatcher'     => $formMapper->getFormBuilder()->getEventDispatcher(),
                                        'required' => false
					))
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
