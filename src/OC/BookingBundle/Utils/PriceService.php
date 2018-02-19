<?php

namespace OC\BookingBundle\Utils;

class PriceService
{
    public function priceAction(\DateTime $birthDate, $reduct, array $prices)
    {
        $today = new \DateTime();
        $age = $today->diff($birthDate);
        if ($reduct) {
            return $prices['reduct'];
        } else {
            switch ($age) {
                case $age->y >= 4 && $age->y < 12:
                    return $prices['junior'];
                    break;

                case $age->y >= 12 && $age->y < 60:
                    return $prices['normal'];
                    break;

                case $age->y >= 60:
                    return $prices['senior'];
                    break;
            }
        }
       /* if ($glassHalfFull)
        {
            $rabbat = $prices / 2 ;
            $prices = $rabbat ;
            return $prices;
        }*/
    }

    public function getTotal($ticketsList, array $prices)
    {
        $price = 0;
        foreach ($ticketsList as $ticket) {
            $price += $this->priceAction($ticket->getBirthDate(), $ticket->getReduct(), $prices);
        }

        return $price;
    }

}