<?php

namespace OC\BookingBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints\DateTime;


/**
 * Booking
 *
 * @ORM\Table(name="booking)
 * @ORM\Entity(repositoryClass="OC\BookingBundle\Repository\BookingRepository"
 */

class Booking
{
    /**
     * @var int
     *
     * @ORM\Column(name="id , type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
private $id;

/**
 * @var DateTime
 *
 * @ORM\Column(name="date", type="datetime")
 */
private $date

/**
 * @var int
 *
 * @ORM\Column(name="total", type="integer")
 */
    private $totalCost;
}