<?php
namespace OC\BookingBundle\Utils;

class GeneratorNumberService
{
    public function GenerateAction()
    {
        $today = date("Ymd");
        $rand = strtoupper(substr(uniqid(sha1(time())),0,4));

        $numberBooking = $today . $rand ;

        return $numberBooking;
    }
}