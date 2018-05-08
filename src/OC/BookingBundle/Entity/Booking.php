<?php

namespace OC\BookingBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints AS Assert;


/**
 * Booking
 *
 * @ORM\Table(name="booking")
 * @ORM\Entity(repositoryClass="OC\BookingBundle\Repository\BookingRepository")
 */
class Booking
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
     * @var string
     *
     * @ORM\Column(name="email", type="string")
     * @Assert\Email
     */
    private $email;


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
     * Set email
     *
     * @param string $email
     *
     * @return Booking
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
}
