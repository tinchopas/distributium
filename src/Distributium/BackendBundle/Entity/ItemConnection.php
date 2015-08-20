<?php

namespace Distributium\BackendBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ItemConnection
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Distributium\BackendBundle\Entity\ItemConnectionRepository")
 */
class ItemConnection
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
     * @ORM\ManyToOne(targetEntity="Item", inversedBy="ic")
     * @ORM\JoinColumn(name="item_id", referencedColumnName="id")
     * */
    protected $item;

    /**
     * @ORM\ManyToOne(targetEntity="Item", inversedBy="cwi")
     * @ORM\JoinColumn(name="cwi_id", referencedColumnName="id")
     * */
    protected $cwi;


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
     * @return ItemConnection
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
     * Set cwi
     *
     * @param Object
     * @return ItemConnection
     */
    public function setCwi($cwi)
    {
        $this->cwi = $cwi;

        return $this;
    }

    /**
     * Get cwi
     *
     * @return Object
     */
    public function getCwi()
    {
        return $this->cwi;
    }

}
