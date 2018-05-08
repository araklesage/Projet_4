<?php
// src/OC/BookingBundle/Validator/NotIntoThePastValidator.php

namespace OC\BookingBundle\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;


class NotIntoThePastValidator extends ConstraintValidator
{
    public function validate($date, Constraint $constraint)
    {
        $today= new \DateTime();

        if ($date <= $today){
            $this->context->addViolation($constraint->message);
        }
    }
}