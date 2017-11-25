<?php

namespace OC\BookingBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Basket
 *
 * @ORM\Table(name="basket")
 * @ORM\Entity(repositoryClass="OC\BookingBundle\Repository\BasketRepository")
 */
class Basket
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
     * @var array
     *
     * @ORM\Column(name="ticketsList", type="array")
     */
    private $ticketsList;


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
     * Set ticketsList
     *
     * @param array $ticketsList
     *
     * @return Basket
     */
    public function setTicketsList($ticketsList)
    {
        $this->ticketsList = $ticketsList;

        return $this;
    }

    /**
     * Get ticketsList
     *
     * @return array
     */
    public function getTicketsList()
    {
        return $this->ticketsList;
    }
}

