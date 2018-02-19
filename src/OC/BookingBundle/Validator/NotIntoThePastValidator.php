<?php
// src/OC/BookingBundle/Validator/NotIntoThePastValidator.php

namespace OC\BookingBundle\Validator;




class NotIntoThePastValidator
{

    public function toLate($date)
    {


        $today= new \DateTime();

        if ($date < $today){
           return true;
        }
    }
}