<?php

namespace OC\BookingBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Booking
 *
 * @ORM\Table(name="booking")
 * @ORM\Entity(repositoryClass="OC\BookingBundle\Repository\BookingRepository")
 */
class Booking
{
    /**
     * @ORM\OneToOne(targetEntity="OC\BookingBundle\Entity\Customer", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $customer;

    /**
     * @ORM\
     */


    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime")
     */
    private $date;

    /**
     * @var int
     *
     * @ORM\Column(name="numberBooking", type="integer")
     */
    private $numberBooking;

    /**
     * @var array
     *
     * @ORM\Column(name="ticketsList", type="array")
     */
    private $ticketsList;


    public function __construct()
    {
        // Par défaut, la date de la commande est la date d'aujourd'hui
        $this->date = new \Datetime();
    }

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
     * Set date
     *
     * @param \DateTime $date
     *
     * @return Booking
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set numberBooking
     *
     * @param integer $numberBooking
     *
     * @return Booking
     */
    public function setNumberBooking($numberBooking)
    {
        $this->numberBooking = $numberBooking;

        return $this;
    }

    /**
     * Get numberBooking
     *
     * @return int
     */
    public function getNumberBooking()
    {
        return $this->numberBooking;
    }

    /**
     * Set rabatt
     *
     * @param boolean $rabatt
     *
     * @return Booking
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


    /**
     * Set customer
     *
     * @param \OC\BookingBundle\Entity\Customer $customer
     *
     * @return Booking
     */
    public function setCustomer(\OC\BookingBundle\Entity\Customer $customer = null)
    {
        $this->customer = $customer;

        return $this;
    }

    /**
     * Get customer
     *
     * @return \OC\BookingBundle\Entity\Customer
     */
    public function getCustomer()
    {
        return $this->customer;
    }
}
