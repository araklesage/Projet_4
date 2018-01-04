<?php

namespace OC\BookingBundle\Utils;

class PriceService
{
    public function priceAction(\DateTime $birthDate, array $prices)
    {
        $today = new \DateTime();
        $age = $today -> diff($birthDate);
        switch ($age)
        {
            case $age->y >=4 && $age->y < 12:
                return $prices['junior'];
            break;

            case $age->y >=12 &&$age->y < 60:
                return $prices['normal'];
            break;

            case $age->y >=60:
                return $prices['senior'];
            break;
        }

    }

}