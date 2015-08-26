<?php

namespace Distributium\BackendBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

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
     * @ORM\Column(name="email", type="string", length=255, nullable=true)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text")
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="raw_description", type="text")
     */
    private $rawDescription;

    /**
     * @var string
     *
     * @ORM\Column(name="description_formatter", type="string", length=255)
     */
    private $descriptionFormatter;

    /**
     * @var integer
     *
     * @ORM\Column(name="lodging_size_from", type="integer", nullable=true)
     */
    private $lodgingSizeFrom;

    /**
     * @var integer
     *
     * @ORM\Column(name="lodging_size_to", type="integer", nullable=true)
     */
    private $lodgingSizeTo;

    /**
     * @var integer
     *
     * @ORM\Column(name="lodgingCategory", type="simple_array", nullable=true)
     */
    private $lodgingCategory;

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
     * @ORM\OneToMany(targetEntity="ItemConnection" , mappedBy="item" , cascade={"all"}, orphanRemoval=true)
     * */
    private $ic;

    /**
     * @ORM\OneToMany(targetEntity="ItemConnection" , mappedBy="cwi" , cascade={"all"}, orphanRemoval=true)
     * */
    private $cwi;

    private $myConnections;

    /**
     * @var Object
     *
     * @ORM\ManyToOne(targetEntity="Company")
     * @ORM\JoinColumn(name="company_id", referencedColumnName="id")
     */
    private $company;

    /**
     * @ORM\ManyToMany(targetEntity="LodgingType", inversedBy="items")
     * @ORM\JoinTable(name="item_lodgingtype")
     *
     * */
    private $lodgingTypes;

    public function __construct() {
        $this->ic = new \Doctrine\Common\Collections\ArrayCollection();
        $this->cwi = new \Doctrine\Common\Collections\ArrayCollection();
        $this->myConnections = new \Doctrine\Common\Collections\ArrayCollection();
        $this->lodgingTypes = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set email
     *
     * @param string $email
     * @return Item
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail()
    {
        return $this->email;
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
     * Set rawDescription
     *
     * @param string $rawDescription
     * @return Item
     */
    public function setRawDescription($rawDescription)
    {
        $this->rawDescription = $rawDescription;

        return $this;
    }

    /**
     * Get rawDescription
     *
     * @return string 
     */
    public function getRawDescription()
    {
        return $this->rawDescription;
    }

    /**
     * Set descriptionFormatter
     *
     * @param string $descriptionFormatter
     * @return Item
     */
    public function setDescriptionFormatter($descriptionFormatter)
    {
        $this->descriptionFormatter = $descriptionFormatter;

        return $this;
    }

    /**
     * Get descriptionFormatter
     *
     * @return string 
     */
    public function getDescriptionFormatter()
    {
        return $this->descriptionFormatter;
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

    public function __toString()
    {
        return $this->getName();
    }

    // Important 
    public function getMyConnection()
    {
        $myConnections = new ArrayCollection();

        foreach($this->ic as $ic)
        {
            $myConnections[] = $ic->getCwi();
        }
        foreach($this->cwi as $ic)
        {
            $myConnections[] = $ic->getItem();
        }

        return $myConnections;
    }

    // Important
    public function setMyConnection($myConnections)
    {
        $origConnections = $this->getMyConnection();

        foreach($myConnections as $i) {
            if (!$origConnections->contains($i)) {
                $ic = new ItemConnection();

                $ic->setItem($this);
                $ic->setCwi($i);

                $this->addIc($ic);
            }
            $origConnections->removeElement($i);

        }

        foreach ($origConnections as $oc) {
            foreach($this->ic as $ic)
            {
                if ($ic->getCwi()->getId() == $oc->getId()) {
                    $this->removeIc($ic);
                }
            }
            foreach($this->cwi as $ic)
            {
                if ($ic->getItem()->getId() == $oc->getId()) {
                    $this->removeCwi($ic);
                    $icToRemove = $ic;
                }
            }
        }
    }

    public function getItem()
    {
        return $this;
    }

    public function addIc($itemConnection)
    {
        $this->ic[] = $itemConnection;
    }
    
    public function getIc()
    {
        return $this->ic;
    }
    
    public function removeIc($itemConnection)
    {
        return $this->ic->removeElement($itemConnection);
    }

    public function removeCwi($itemConnection)
    {
        return $this->cwi->removeElement($itemConnection);
    }

    public function addCwi($itemConnection)
    {
        $this->cwi[] = $itemConnection;
    }
    
    public function getCwi()
    {
        return $this->cwi;
    }

    /**
     * Set company
     *
     * @param Company $company
     * @return Item
     */
    public function setCompany($company)
    {
        $this->company = $company;

        return $this;
    }

    /**
     * Get company
     *
     * @return Company
     */
    public function getCompany()
    {
        return $this->company;
    }

    public function addLodgingType($lodgingType)
    {
        $this->lodgingTypes[] = $lodgingType;
    }
    
    public function getLodgingType()
    {
        return $this->lodgingTypes;
    }
    
    public function removeLodgingType($lodgingType)
    {
        return $this->lodgingTypes->removeElement($lodgingType);
    }

    /**
     * Set lodgingSizeFrom
     *
     * @param Integer $lodgingSizeFrom
     * @return Item
     */
    public function setLodgingSizeFrom($lodgingSizeFrom)
    {
        $this->lodgingSizeFrom = $lodgingSizeFrom;

        return $this;
    }

    /**
     * Get lodgingSizeFrom
     *
     * @return Integer
     */
    public function getLodgingSizeFrom()
    {
        return $this->lodgingSizeFrom;
    }

    /**
     * Set lodgingSizeTo
     *
     * @param Integer $lodgingSizeTo
     * @return Item
     */
    public function setLodgingSizeTo($lodgingSizeTo)
    {
        $this->lodgingSizeTo = $lodgingSizeTo;

        return $this;
    }

    /**
     * Get lodgingSizeTo
     *
     * @return Integer
     */
    public function getLodgingSizeTo()
    {
        return $this->lodgingSizeTo;
    }

    /**
     * Set lodgingCategory
     *
     * @param Array $lodgingCategory
     * @return Item
     */
    public function setLodgingCategory($lodgingCategory)
    {
        $this->lodgingCategory = $lodgingCategory;

        return $this;
    }

    /**
     * Get lodgingCategory
     *
     * @return Array
     */
    public function getLodgingCategory()
    {
        return $this->lodgingCategory;
    }
}
