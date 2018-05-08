<?php

namespace OC\BookingBundle\Entity;

use Symfony\Component\Validator\Constraint as Assert;

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
     *
     *
     *
     * @ORM\Column(name="cardNumber", type="integer")
     */
    private $cardNumber;

    /**
     * @var int
     *
     * @ORM\Column(name="cvc", type="integer")
     */
    private $cvc;

    /**
     * @var int
     *
     *
     *
     * @ORM\Column(name="expirationDateMonth", type="integer")
     */
    private $expirationDateMonth;

    /**
     * @var int
     *
     * @ORM\Column(name="expirationDateYear", type="integer")
     */
    private $expirationDateYear;

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
     * Set cvc
     *
     * @param integer $cvc
     *
     * @return Customer
     */
    public function setCvc($cvc)
    {
        $this->cvc = $cvc;

        return $this;
    }

    /**
     * Get cvc
     *
     * @return int
     */
    public function getCvc()
    {
        return $this->cvc;
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

    /**
     * Set expirationDateMonth
     *
     * @param integer $expirationDateMonth
     *
     * @return Customer
     */
    public function setExpirationDateMonth($expirationDateMonth)
    {
        $this->expirationDateMonth = $expirationDateMonth;

        return $this;
    }

    /**
     * Get expirationDateMonth
     *
     * @return integer
     */
    public function getExpirationDateMonth()
    {
        return $this->expirationDateMonth;
    }

    /**
     * Set expirationDateYear
     *
     * @param integer $expirationDateYear
     *
     * @return Customer
     */
    public function setExpirationDateYear($expirationDateYear)
    {
        $this->expirationDateYear = $expirationDateYear;

        return $this;
    }

    /**
     * Get expirationDateYear
     *
     * @return integer
     */
    public function getExpirationDateYear()
    {
        return $this->expirationDateYear;
    }
}
