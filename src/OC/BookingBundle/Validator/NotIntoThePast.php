<?php
// src/OC/BookingBundle/Validator/NotIntoThePast.php

namespace OC\BookingBundle\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class NotIntoThePast extends Constraint
{
    public $message ="Vous ne pouvez pas réserver pour un jour passé.";

}