<?php

namespace Distributium\BackendBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * LodgingFeature
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class LodgingFeature
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var Object
     *
     * @ORM\ManyToMany(targetEntity="Item", mappedBy="lodgingFeatures")
     *
     */
    private $items;


    public function __construct() {
        $this->items = new \Doctrine\Common\Collections\ArrayCollection();
    }


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return LodgingFeature
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set Items
     *
     * @param arrayCollection $items
     * @return LodgingFeature
     */
    public function setItems($items)
    {
        $this->items = $items;

        return $this;
    }

    /**
     * Get items
     *
     * @return ArrayCollection
     */
    public function getItems()
    {
        return $this->items;
    }

    public function __toString()
    {
        return $this->getName();
    }
}
