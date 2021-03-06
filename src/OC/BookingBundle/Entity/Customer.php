<?php

namespace OC\BookingBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Customer
 *
 * @ORM\Table(name="customer")
 * @ORM\Entity(repositoryClass="OC\BookingBundle\Repository\CustomerRepository")
 */
class Customer
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
     * @ORM\Column(name="cardName", type="string", length=255)
     */
    private $cardName;

    /**
     * @var int
     *
     * @ORM\Column(name="cardNumber", type="integer")
     */
    private $cardNumber;

    /**
     * @var int
     *
     * @ORM\Column(name="cvv", type="integer")
     */
    private $cvv;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="expirationDate", type="datetime")
     */
    private $expirationDate;


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
     * Set cardNumber
     *
     * @param integer $cardNumber
     *
     * @return Customer
     */
    public function setCardNumber($cardNumber)
    {
        $this->cardNumber = $cardNumber;

        return $this;
    }

    /**
     * Get cardNumber
     *
     * @return int
     */
    public function getCardNumber()
    {
        return $this->cardNumber;
    }

    /**
     * Set cvv
     *
     * @param integer $cvv
     *
     * @return Customer
     */
    public function setCvv($cvv)
    {
        $this->cvv = $cvv;

        return $this;
    }

    /**
     * Get cvv
     *
     * @return int
     */
    public function getCvv()
    {
        return $this->cvv;
    }

    /**
     * Set expirationDate
     *
     * @param \DateTime $expirationDate
     *
     * @return Customer
     */
    public function setExpirationDate($expirationDate)
    {
        $this->expirationDate = $expirationDate;

        return $this;
    }

    /**
     * Get expirationDate
     *
     * @return \DateTime
     */
    public function getExpirationDate()
    {
        return $this->expirationDate;
    }

    /**
     * Set cardName
     *
     * @param string $cardName
     *
     * @return Customer
     */
    public function setCardName($cardName)
    {
        $this->cardName = $cardName;

        return $this;
    }

    /**
     * Get cardName
     *
     * @return string
     */
    public function getCardName()
    {
        return $this->cardName;
    }
}
