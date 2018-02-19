<?php
// src/OC/BookingBundle/Validator/NotIntoThePast.php

namespace OC\BookingBundle\Validator;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraint;


/**
 * @Annotation
 */
class NotIntoThePast extends Constraint
{

    public function validatedBy()
    {
        return 'oc_booking_notIntoThePast';
    }


}