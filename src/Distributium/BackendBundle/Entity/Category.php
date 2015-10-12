<?php

namespace Distributium\BackendBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Category
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Category
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
     * @var boolean
     *
     * @ORM\Column(name="allow_integration", type="boolean", options={"default" = 0})
     */
    private $allowIntegration = 0;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

    /**
     * @var Object
     *
     * @ORM\OneToOne(targetEntity="Logo", inversedBy="category", cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="logo_id", referencedColumnName="id")
     */
    private $logo;

    /**
     * @ORM\OneToMany(targetEntity="Item" , mappedBy="category")
     * */
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
     * @return Category
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
     * Set allowIntegration
     *
     * @param boolean $allowIntegration
     * @return Category
     */
    public function setAllowIntegration($allowIntegration)
    {
        $this->allowIntegration = $allowIntegration;

        return $this;
    }

    /**
     * Get allowIntegration
     *
     * @return booleand
     */
    public function getAllowIntegration()
    {
        return $this->allowIntegration;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Category
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Get logo.
     *
     * @return logo.
     */
    public function getLogo()
    {
        return $this->logo;
    }

    /**
     * Set logo.
     *
     * @param logo the value to set.
     */
    public function setLogo($logo)
    {
        $this->logo = $logo;

        return $this;
    }

    function __toString() {
        return $this->getName();
    }

    function getIdentifier() {
        return sprintf('_%s', $this->getId());
    }

    /**
     * Set items
     *
     * @param string $items
     * @return Category
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
}
