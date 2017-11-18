<?php

namespace OC\BookingBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Ticket
 *
 * @ORM\Table(name="ticket")
 * @ORM\Entity(repositoryClass="OC\BookingBundle\Repository\TicketRepository")
 */
class Ticket
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="Title", type="string", length=255)
     */
    private $title;

    /**
     * @var int
     *
     * @ORM\Column(name="price", type="integer")
     */
    private $price;

    /**
     * @var bool
     *
     * @ORM\Column(name="fullOrNot", type="boolean")
     */
    private $fullOrNot;




    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set title
     *
     * @param string $title
     *
     * @return Ticket
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set price
     *
     * @param integer $price
     *
     * @return Ticket
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price
     *
     * @return int
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set fullOrNot
     *
     * @param boolean $fullOrNot
     *
     * @return Ticket
     */
    public function setFullOrNot($fullOrNot)
    {
        $this->fullOrNot = $fullOrNot;

        return $this;
    }

    /**
     * Get fullOrNot
     *
     * @return bool
     */
    public function getFullOrNot()
    {
        return $this->fullOrNot;
    }

    /**
     * Set reduct
     *
     * @param boolean $reduct
     *
     * @return Ticket
     */
    public function setReduct($reduct)
    {
        $this->reduct = $reduct;

        return $this;
    }

    /**
     * Get reduct
     *
     * @return bool
     */
    public function getReduct()
    {
        return $this->reduct;
    }
}

