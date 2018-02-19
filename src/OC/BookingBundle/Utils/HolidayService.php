<?php
namespace OC\BookingBundle\Utils;

class HolidayService
{
    public function DateValidator($date, array $holiday)
    {
    $newDate = date('w', strtotime($date->format('Y-m-d')));
    $ferie = date('m-d', strtotime($date->format('Y-m-d')));

        if ($newDate == 0 || 2){
            return true;
        }
        else if (in_array($ferie, $holiday)){
           return true;

        }
        else{
            return false;
        }

    }
}