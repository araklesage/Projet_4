<?php
/**
 * Created by PhpStorm.
 * User: Arak
 * Date: 15/01/2018
 * Time: 16:12
 */

namespace OC\BookingBundle\Utils;


class StripeService
{
    /**
     * @Route(
     *     "/checkout",
     *     name="order_checkout",
     *     methods="POST"
     * )
     */
    public function checkoutAction()
    {
        \Stripe\Stripe::setApiKey("SK_PUBLIC_TEST_API");

        // Get the credit card details submitted by the form
        $token = $_POST['stripeToken'];

        // Create a charge: this will charge the user's card
        try {
            $charge = \Stripe\Charge::create(array(
                "amount" => 1000, // Amount in cents
                "currency" => "eur",
                "source" => $token,
                "description" => "Paiement Stripe - OpenClassrooms Exemple"
            ));
            $this->addFlash("success","Bravo ça marche !");
            return $this->redirectToRoute("order_prepare");
        } catch(\Stripe\Error\Card $e) {

            $this->addFlash("error","Snif ça marche pas :(");
            return $this->redirectToRoute("order_prepare");
            // The card has been declined
        }
    }
}