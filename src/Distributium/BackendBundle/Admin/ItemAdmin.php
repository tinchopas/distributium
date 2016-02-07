<?php

namespace Distributium\BackendBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Sonata\CoreBundle\Validator\ErrorElement;



class ItemAdmin extends Admin
{
    private $categories = array();
    private $ff;
    private $formMapper;

    /** @var \Symfony\Component\DependencyInjection\ContainerInterface */
    private $container;

    public function setContainer (\Symfony\Component\DependencyInjection\ContainerInterface $container) {
        $this->container = $container;
    }

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

	if ($myCategory == null || $myCategory->getAllowIntegration() != true) {
		return;
	}

        $em = $this->modelManager->getEntityManager('DistributiumBackendBundle:Category');
        $query = $em->createQueryBuilder('c')
            ->select('c')
            ->from('DistributiumBackendBundle:Category', 'c')
	    ->where('c.id IS NOT NULL')
	    ->andWhere('c.allowIntegration = true');

        $this->categories = $query->getQuery()->getResult();
        if ($this->categories == null) {
            $this->categories = array();
        }

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


        $categoryDisabled = false;
        if (count($this->getSubject()->getIc()) != 0 || 
            count($this->getSubject()->getCwi()) != 0) {
                $categoryDisabled = true;
            }

        $formMapper
            ->with('General', array('class' => 'col-md-12'))
            ->add('name', 'text', array('label' => 'Name'))
            ->add('email', 'text', array('label' => 'Email'))
            ->add('logo', 'sonata_type_admin', array('required' => false))
            /*
            ->add('media', 'sonata_media_type', array(
                'provider' => 'sonata.media.provider.image',
                'context'  => 'default'
            ))
             */
            ->add('itemHasImage', 'sonata_type_collection', array(
                'cascade_validation' => false,
                'required' => false,
                'by_reference' => false,
                'btn_catalogue' => true,
                 
               

                'type_options' => array(
                    // Prevents the "Delete" option from being displayed
                    'delete' => true,
                    'delete_options' => array(
                        // You may otherwise choose to put the field but hide it
                        'type'         => 'checkbox',
                        // In that case, you need to fill in the options as well
                        'type_options' => array(
                            'mapped'   => false,
                            'required' => false,
                        )
                    )
                )


 
            ), array(
                'edit' => 'inline',
                'inline' => 'table',
                'link_parameters' => array('context' => 'widget'),
                'admin_code' => 'distributium_backend.admin.item_has_image' 
            ))
            ->add('company', 'entity', array('label' => 'Company', 'class' => 'Distributium\BackendBundle\Entity\Company', 'required' => false))
            ->add('category', 'entity', array('label' => 'Category', 'class' => 'Distributium\BackendBundle\Entity\Category', 'required' => false))
            ->add('myConnection', 'sonata_type_model', $connectionOptions)
            ->add('shortDescription', 'text', array('label' => 'Short Description'))
            ->add('description', 'sonata_formatter_type', array(
                'source_field'         => 'rawDescription',
                'source_field_options' => array('attr' => array('class' => 'span6', 'rows' => 10)),
                'format_field'         => 'descriptionFormatter',
                'target_field'         => 'description',
                'ckeditor_context'     => 'default',
                'event_dispatcher'     => $formMapper->getFormBuilder()->getEventDispatcher(),
                'required' => false
            ))
            ->end()
            ;

        $lodgingSizesChoices = $this->container->getParameter('lodgingSize');
        $lodgingCategoriesChoices = $this->container->getParameter('lodgingCategory');
        $formMapper
            ->with('Filters', array('class' => 'col-md-12'))
            ->add('lodgingSize', 'choice', array(
                'required' => false,
                'multiple' => true, 
                'sortable' => true, 
                'choices' =>  $lodgingSizesChoices
            ))
            ->add('lodgingCategory', 'choice', array(
                'required' => false,
                'multiple' => true, 
                'sortable' => true, 
                'choices' => $lodgingCategoriesChoices
            ))
            ->add('lodgingType', 'sonata_type_model', array('required' => false, 'class' => 'Distributium\BackendBundle\Entity\LodgingType', 'multiple'=>true, 'btn_add'=>false))
            ->add('lodgingFeature', 'sonata_type_model', array('required' => false, 'class' => 'Distributium\BackendBundle\Entity\LodgingFeature', 'multiple'=>true, 'btn_add'=>false))
            ->end()
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

    public function validate(ErrorElement $errorElement, $object)
    {
        $errorElement
            ->with('shortDescription')
                ->assertLength(array('max' => 300))
            ->end()
        ;
    }

    public function prePersist($page)
    {
        $this->manageEmbeddedImageAdmins($page);

    }

    public function preUpdate($page)
    {
        $this->manageEmbeddedImageAdmins($page);
        foreach ($page->getItemHasImage() as $itemHasImage) {
            $itemHasImage->setItem($page);
        }
    }

    private function manageEmbeddedImageAdmins($page)
    {
        // Cycle through each field
        foreach ($this->getFormFieldDescriptions() as $fieldName => $fieldDescription) {
            // detect embedded Admins that manage Images
            if ($fieldDescription->getType() === 'sonata_type_admin' &&
                ($associationMapping = $fieldDescription->getAssociationMapping()) &&
                $associationMapping['targetEntity'] === 'Distributium\BackendBundle\Entity\Logo'
            ) {
                $getter = 'get'.$fieldName;
                $setter = 'set'.$fieldName;

                /** @var Image $image */
                $image = $page->$getter();

                if ($image) {
                    if ($image->getFile()) {
                        // update the Image to trigger file management
                        $image->refreshUpdated();
                    } elseif (!$image->getFile() && !$image->getWebPath()) {
                        // prevent Sf/Sonata trying to create and persist an empty Image
                        $page->$setter(null);
                    }
                }
            }
        }
    }

    public function getFormTheme()
    {
        return array_merge(
            parent::getFormTheme(),
            array('DistributiumBackendBundle:Form:admin.theme.html.twig')
        );
    }
}
