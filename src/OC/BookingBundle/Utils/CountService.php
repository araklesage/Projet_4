<?php
namespace OC\BookingBundle\Utils;

class CountService
{
    public function ToMuchTicket($listTickets)
    {

        $count = count($listTickets);

        if ($count < 1000 ) {
            return true;
    }
        else {
            return false;
        }

    }
}