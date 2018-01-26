<?php
// src/OC/BookingBundle/Controller/BookingController.php

namespace OC\BookingBundle\Controller;

use OC\BookingBundle\Entity\Booking;
use OC\BookingBundle\Entity\Contact;
use OC\BookingBundle\Entity\Customer;
use OC\BookingBundle\Entity\Ticket;
use OC\BookingBundle\Form\BookingType;
use OC\BookingBundle\Form\ContactType;
use OC\BookingBundle\Form\CustomerType;
use OC\BookingBundle\Form\TicketDeleteType;
use OC\BookingBundle\Form\TicketType;
use OC\BookingBundle\Utils\PriceService;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;


class BookingController extends Controller
{

    /*Retravailler le design du site avec un infinite scrolling comprenant deux pages :
    ->page d'accueil = Image du louvre et titre
    ->page next = présentation des différents tickets
    */

    // Page d'accueil
    public function indexAction()
    {
        return $this->render('OCBookingBundle:Ticket:index.html.twig');
    }

    // Appel Page de confirmation de commande et envoi Email

    public function confirmAction(Request $request)
    {
        $session = $request->getSession();
        $tickets = ($session->get('tickets')) ? $session->get('tickets') : array();
        $em = $this->getDoctrine()->getManager();
        $booking = ($session->get('booking')) ? $session->get('booking') : array();
        foreach ($tickets as $ticket) {
            $ticket->setBooking($booking);
            $em->persist($ticket);
        }
        $em->persist($booking);
        $em->flush();

        $message = (new \Swift_Message('Validation de réservation'))
            ->setFrom('estebangrabette@gmail.com')
            ->setTo('{{booking.email}}')
            ->setBody(
                $this->renderView(
                    'OCBookingBundle:Emails:validation.html.twig',
                    array()
                ),
                'text/html'
            );

        $this->get('mailer')->send($message);

        $request->getSession()->getFlashbag()->add('success', 'Commande validée');

        return $this->render('OCBookingBundle:Ticket:confirm.html.twig');
    }

    // Formulaire d'Ajout Ticket

    public function formAction(Request $request)
    {
        $ticket = new Ticket();
        $form = $this->get('form.factory')->create(TicketType::class, $ticket);

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);

            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $session = $request->getSession();
                $tickets = ($session->get('tickets')) ? $session->get('tickets') : array();
                $ticket->sessionId = uniqid();
                $tickets[] = $ticket;
                $session->set('tickets', $tickets);

                $request->getSession()->getFlashBag()->add('success', 'Ticket ajouté');

                return $this->render('OCBookingBundle:Ticket:form.html.twig', array(
                    "form" => $form->createView(),
                    "tickets" => $tickets,
                ));
            }
        }
        $request->getSession()->getFlashBag()->add('fail', 'Vous ne pouvez pas réserver pour le jour présent ou une date ultérieure.');

        return $this->render('OCBookingBundle:Ticket:form.html.twig', array(
            "form" => $form->createView(),
        ));
    }

    //Appel du Panier

    public function menuAction()
    {
        return $this->render('basket.html.twig');
    }

    //Appel du formulaire de contact

    public function contactAction(Request $request)
    {
        $contact = new Contact;
        $form = $this->get('form.factory')->create(ContactType::class, $contact);
        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($contact);
            $em->flush();

            $request->getSession()->getFlashBag()->add('notice', 'Annonce bien enregistrée.');

            return $this->redirectToRoute('oc_platform_home');
        }

        return $this->render('OCBookingBundle:Contact:contact.html.twig', array(
            'form' => $form->createView(),
        ));

    }

    // Validation Panier et appel bouton de paiement Stripe ( à travailler)

    public function validationAction(Request $request)
    {

        $booking = new booking;
        $session = $request->getSession();
        $tickets = ($session->get('tickets')) ? $session->get('tickets') : array();
        if (count($tickets) == 0) {
            $request->getSession()->getFlashBag()->add('fail', 'Votre panier est vide');

            return $this->redirectToRoute('oc_booking_homepage');
        } else if ($request->isMethod('POST') && $bookingform->handleRequest($request)->isValid()) {
            $generatorService = $this->container->get('oc_generator.number');
            $booking->setNumberBooking($generatorService->GenerateAction());
            $session->set("booking", $booking);
            $em = $this->getDoctrine()->getManager();
            $booking = new Booking();
            $booking = ($session->get('booking'));
            $booking->sessionId = uniqid();
            $tickets[] = $ticket;
            $session->set('tickets', $tickets);
            foreach ($tickets as $ticket) {
                $ticket->setBooking($booking);
                $em->persist($ticket);
            }
            $em->persist($booking);
            $em->flush();
            $customer = new Customer();
            $card = $this->get('form.factory')->create(CustomerType::class, $customer);

            return $this->redirectToRoute('oc_booking_homepage');
        }
        return $this->redirectToRoute('oc_booking_homepage');

        $request->getSession()->getFlashBag()->add('fail', "L'Email n'est pas correcte");
    }

    // Gestionnaire de l'affichage de la liste de ticket du Panier

    public function panierAction(Request $request)
    {
        $booking = new booking;
        $bookingform = $this->get('form.factory')->create(BookingType::class, $booking);
        $priceService = $this->container->get('oc_booking.prices');
        $session = $request->getSession();
        if ($session->get('tickets')) {
            $tickets = $session->get('tickets');
            if (count($tickets) > 0) {
                foreach ($tickets as $ticket) {
                    $ticket->price = $priceService->priceAction($ticket->getBirthDate(), $ticket->getReduct(), $this->getParameter('prices'));
                }
            } else {
                $tickets = array();
            }

            $total = $priceService->getTotal($tickets, $this->getParameter('prices'));

            if ($request->isMethod('POST') && $bookingform->handleRequest($request)->isValid()) {
                $generatorService = $this->container->get('oc_generator.number');
                $booking->setNumberBooking($generatorService->GenerateAction());
                $booking = ($session->get('booking'));
                $session->set('booking', $booking);

            }

            return $this->render('OCBookingBundle:Ticket:basket.html.twig', array(
                "tickets" => $tickets,
                "total" => $total,
                "booking" => $bookingform->createView(),
            ));
        }
    }

    // Action de suppresion de ticket.

    public function deleteAction(Request $request, $sessionId)
    {
        $session = $request->getSession();
        $tickets = $session->get('tickets');;
        foreach ($tickets as $key => $ticket) {
            if ($ticket->sessionId == $sessionId) {
                unset($tickets[$key]);
                $this->get('session')->getFlashBag()->add('success', 'Ticket supprimé avec succès !');
            }
        }
        $session->set('tickets', $tickets);
        return $this->redirectToRoute('oc_booking_homepage');
    }
}
/*  public function cardAction()
  {
      $priceService = $this->container->get('oc_booking.prices');
      $total = $priceService->getTotal($tickets, $this->getParameter('prices'));

      return $this->render('OCBookingBundle:Ticket:card.html.twig', array(
          'total' => $total,
          'card' => $card->createView(),
      ));
  }
*/

