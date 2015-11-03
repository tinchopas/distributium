<?php

namespace Distributium\BackendBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * ItemHasImage
 *
 * @ORM\Table()
 * @ORM\Entity()
 */
class ItemHasImage
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
     * @var \Namespace\YourBundle\Entity\Products
     * @Assert\NotBlank()
     * @ORM\ManyToOne(targetEntity="Item", inversedBy="itemHasImage", fetch="LAZY")
     * @ORM\JoinColumn(name="item_id", referencedColumnName="id")
     */
    protected $item;

    /**
     * @var \Application\Sonata\MediaBundle\Entity\Media
     * @Assert\NotBlank()
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Media\Media", cascade={"persist","remove"}, fetch="LAZY")
     * @ORM\JoinColumn(name="media_id", referencedColumnName="id")
     */
    protected $media;


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
     * Set item
     *
     * @param Object
     * @return ItemHasImage
     */
    public function setItem($item)
    {
        $this->item = $item;

        return $this;
    }

    /**
     * Get item
     *
     * @return Object
     */
    public function getItem()
    {
        return $this->item;
    }

    /**
     * Set media
     *
     * @param Object
     * @return ItemHasImage
     */
    public function setMedia($media)
    {
        $this->media = $media;

        return $this;
    }

    /**
     * Get media
     *
     * @return Object
     */
    public function getMedia()
    {
        return $this->media;
    }
}
