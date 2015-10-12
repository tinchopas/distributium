<?php

namespace Distributium\BackendBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Item
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Distributium\BackendBundle\Entity\ItemRepository")
 *
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
     * @ORM\Column(name="shortDescription", type="string", length=1024, nullable=true)
     */
    private $shortDescription;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="raw_description", type="text", nullable=true)
     */
    private $rawDescription;

    /**
     * @var string
     *
     * @ORM\Column(name="description_formatter", type="string", length=255)
     */
    private $descriptionFormatter;


    /**
     * @var simple_array
     *
     * @ORM\Column(name="lodging_size", type="simple_array", nullable=true)
     */
    private $lodgingSize;

    /**
     * @var integer
     *
     * @ORM\Column(name="lodging_size_mask", type="integer", nullable=true)
     */
    private $lodgingSizeMask;

    /**
     * @var simple_array
     *
     * @ORM\Column(name="lodging_category", type="simple_array", nullable=true)
     */
    private $lodgingCategory;

    /**
     * @var integer
     *
     * @ORM\Column(name="lodging_category_mask", type="integer", nullable=true)
     */
    private $lodgingCategoryMask;

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
     * @ORM\OneToOne(targetEntity="Logo", inversedBy="item", cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="logo_id", referencedColumnName="id")
     */
    private $logo;

    /**
     * @ORM\OneToMany(targetEntity="Image" , mappedBy="item" , cascade={"all"}, orphanRemoval=true)
     * */
    private $images;

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

    /**
     * @ORM\ManyToMany(targetEntity="LodgingFeature", inversedBy="items")
     * @ORM\JoinTable(name="item_lodgingfeature")
     *
     * */
    private $lodgingFeatures;

    public function __construct() {
        $this->ic = new \Doctrine\Common\Collections\ArrayCollection();
        $this->cwi = new \Doctrine\Common\Collections\ArrayCollection();
        $this->myConnections = new \Doctrine\Common\Collections\ArrayCollection();
        $this->lodgingTypes = new \Doctrine\Common\Collections\ArrayCollection();
        $this->lodgingFeatures = new \Doctrine\Common\Collections\ArrayCollection();
        $this->images = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set shortDescription
     *
     * @param string $shortDescription
     * @return Item
     */
    public function setShortDescription($shortDescription)
    {
        $this->shortDescription = $shortDescription;

        return $this;
    }

    /**
     * Get shortDescription
     *
     * @return string 
     */
    public function getShortDescription()
    {
        return $this->shortDescription;
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

    /**
     * Get images.
     *
     * @return ArrayCollection.
     */
    public function getImages()
    {
        return $this->images;
    }

    /**
     * Set images.
     *
     * @param images the value to set.
     */
    public function setImages($images)
    {
        $this->images = $images;

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

    public function addLodgingFeature($lodgingFeature)
    {
        $this->lodgingFeatures[] = $lodgingFeature;
    }
    
    public function getLodgingFeature()
    {
        return $this->lodgingFeatures;
    }
    
    public function removeLodgingFeature($lodgingFeature)
    {
        return $this->lodgingFeatures->removeElement($lodgingFeature);
    }

    /**
     * Set lodgingSize
     *
     * @param Array $lodgingSize
     * @return Item
     */
    public function setLodgingSize($lodgingSize)
    {
        $this->lodgingSize = $lodgingSize;
        $this->lodgingSizeMask = bindec(array_sum($lodgingSize));

        return $this;
    }

    /**
     * Get lodgingSize
     *
     * @return Array
     */
    public function getLodgingSize()
    {
        return $this->lodgingSize;
    }

    /**
     * Set lodgingSizeMask
     *
     * @param Integer $lodgingSizeMask
     * @return Item
     */
    public function setLodgingSizeMask($lodgingSizeMask)
    {
        $this->lodgingSizeMask = $lodgingSizeMask;

        return $this;
    }

    /**
     * Get lodgingSizeMask
     *
     * @return Integer
     */
    public function getLodgingSizeMask()
    {
        return $this->lodgingSizeMask;
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
        $this->lodgingCategoryMask = bindec(array_sum($lodgingCategory));

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

    /**
     * Set lodgingCategoryMask
     *
     * @param Integer $lodgingCategoryMask
     * @return Item
     */
    public function setLodgingCategoryMask($lodgingCategoryMask)
    {
        $this->lodgingCategoryMask = $lodgingCategoryMask;

        return $this;
    }

    /**
     * Get lodgingCategoryMask
     *
     * @return Integer
     */
    public function getLodgingCategoryMask()
    {
        return $this->lodgingCategoryMask;
    }
}
