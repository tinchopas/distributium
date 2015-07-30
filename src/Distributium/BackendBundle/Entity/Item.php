<?php

namespace Distributium\BackendBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Item
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Item
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
     * @var string
     *
     * @ORM\Column(name="description", type="text")
     */
    private $description;

    /**
     * @var Object
     *
     * @ORM\ManyToOne(targetEntity="Category")
     * @ORM\JoinColumn(name="category_id", referencedColumnName="id")
     */
    private $category;

    /**
     * @var Object
     *
     * @ORM\OneToOne(targetEntity="Image", inversedBy="item", cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="image_id", referencedColumnName="id")
     */
    private $image;

    /**
     * @ORM\ManyToMany(targetEntity="Item", mappedBy="myConnections")
     **/
    private $connectedWithMe;

    /**
     * @ORM\ManyToMany(targetEntity="Item", inversedBy="friendsWithMe")
     * @ORM\JoinTable(name="connections",
     *      joinColumns={@ORM\JoinColumn(name="item_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="connection_item_id", referencedColumnName="id")}
     *      )
     **/
    private $myConnections;

    public function __construct() {
        $this->connectedWithMe = new \Doctrine\Common\Collections\ArrayCollection();
        $this->myConnections = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return Item
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
     * Set description
     *
     * @param string $description
     * @return Item
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
     * Set category
     *
     * @param Category $category
     * @return Item
     */
    public function setCategory($category)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category
     *
     * @return Category
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Get image.
     *
     * @return image.
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Set image.
     *
     * @param image the value to set.
     */
    public function setImage($image)
    {
        $this->image = $image;

        return $this;
    }

    public function addMyConnection($connection){

        $this->myConnections[] = $connection;

    }

    public function removeMyConnection($connection)
    {
        $this->myConnection->removeElement($connection);
    }
}
