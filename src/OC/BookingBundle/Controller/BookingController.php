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
use Stripe\Charge;
use Stripe\Stripe;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\DependencyInjection\ContainerInterface;
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

    public function validationAction(Request $request)
    {
        $session = $request->getSession();
        $tickets = ($session->get('tickets')) ? $session->get('tickets') : array();
        $booking = ($session->get('booking')) ? $session->get('booking') : array();
        $priceService = $this->container->get('oc_booking.prices');
        $total = $priceService->getTotal($tickets, $this->getParameter('prices'));
        Stripe::setApiKey('sk_test_ujyOAs5oMtAs04rZTLf5RNyj');
        Charge::create(array(
            'amount' => $total * 100,
            'currency' => 'eur',
            'description' => 'paiement test',
            'capture' => false,
            'source' => $request->get('stripeToken'),
        ));

        $em = $this->getDoctrine()->getManager();
        foreach ($tickets as $ticket) {
            $ticket->setBooking($booking);
            $em->persist($ticket);
        }
        $em->persist($booking);
        $em->flush();
        $session->clear();


        return $this->render('OCBookingBundle:Ticket:validation.html.twig');

    }

    // Formulaire d'Ajout Ticket

    public function formAction(Request $request)
    {
        $ticket = new Ticket();
        $form = $this->get('form.factory')->create(TicketType::class, $ticket, [
            'action' => $this->generateUrl('oc_booking_form')
        ]);

        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
            //connexion à la BD et de l'entity ticket
            $repository = $this
                ->getDoctrine()
                ->getManager()
                ->getRepository('OCBookingBundle:Ticket');
            $date = $form['date']->getData();

            // Récupération des tickets en fonction de la date du ticket à valider

            $listTickets = $repository->findByDate($date);

            $notIntoThePastValidator = $this->container->get('oc_booking.notIntoThePast');

            $notIntoThePast = $notIntoThePastValidator->toLate($date);

            // récupération du service
            $holidayService = $this->container->get('oc_booking.date');

            $holiday = $holidayService->dateValidator($date, $this->getParameter('holiday'));
            if (!$notIntoThePast) {

                // si jour non ferié : validation de la suite du formulaire.
                if (!$holiday) {

                    $countService = $this->container->get('oc_booking.counter');

                    // envois de la liste de tickets de la BD au service de comptage

                    $go = $countService->ToMuchTicket($listTickets);

                    // activation de l'enregistrement en session si le signal est possitif

                    if ($go) {
                        $session = $request->getSession();
                        $tickets = ($session->get('tickets')) ? $session->get('tickets') : array();
                        $ticket->sessionId = uniqid();
                        $tickets[] = $ticket;
                        $session->set('tickets', $tickets);

                        $request->getSession()->getFlashBag()->add('success', 'Ticket ajouté');

                        return $this->redirectToRoute('oc_booking_homepage');
                    } // non enregistrement en session si le signal est négatif
                    else {
                        $request->getSession()->getFlashBag()->add('info', "Le nombre d'entrée disponible ce jour est atteint merci de sélectionné une autre date");

                        return $this->redirectToRoute('oc_booking_homepage');
                    }
                } //si jour ferié affichage d'une balise et retour sur l'index
                else {
                    $request->getSession()->getFlashBag()->add('info', 'Vous ne pouvez pas réserver de ticket pour ce jour : ' . $date->format('d-m-Y') . ' est un jour ferié');

                    return $this->redirectToRoute('oc_booking_homepage');
                }
            }
            else{
                $request->getSession()->getFlashBag()->add('fail', 'Vous ne pouvez réserver pour un jour passé');
                return $this->redirectToRoute('oc_booking_homepage');
            }
        }

        return $this->render('OCBookingBundle:Ticket:form.html.twig', array(
            "form" => $form->createView(),
        ));
    }

    //Appel du Panier

    public function menuAction()
    {
        return $this->render('basket.html.twig');
    }

    // Affichage Page tickets

    public function ticketsAction()
    {
        return $this->render('OCBookingBundle:Ticket:tickets.html.twig');
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

            return $this->redirectToRoute('oc_booking_homepage');
        }

        return $this->render('OCBookingBundle:Contact:contact.html.twig', array(
            'form' => $form->createView(),
        ));

    }

    // Confirmation Panier et appel bouton de paiement Stripe

    public function confirmAction(Request $request)
    {
        $session = $request->getSession();
        $booking = ($session->get('booking')) ? $session->get('booking') : new booking;
        $tickets = ($session->get('tickets')) ? $session->get('tickets') : array();
        $priceService = $this->container->get('oc_booking.prices');
        $total = $priceService->getTotal($tickets, $this->getParameter('prices'));

        return $this->render('OCBookingBundle:Ticket:payment.html.twig', array(
            "tickets" => $tickets,
            "total" => $total,
            "booking" => $booking,
        ));
    }

    // Gestionnaire de l'affichage de la liste de ticket du Panier

    public function basketAction(Request $request)
    {
        $booking = new booking;
        $session = $request->getSession();
        $tickets = $session->get('tickets');
        $bookingform = $this->get('form.factory')->create(BookingType::class, $booking, [
            'action' => $this->generateUrl('oc_booking_basket')
        ]);
        $priceService = $this->container->get('oc_booking.prices');

        if (count($tickets) > 0) {
            foreach ($tickets as $ticket) {
                $ticket->price = $priceService->priceAction($ticket->getBirthDate(), $ticket->getReduct(), $this->getParameter('prices'));
            }
        } else {
            $tickets = array();
        }

        $total = $priceService->getTotal($tickets, $this->getParameter('prices'));

        if ($request->isMethod('POST') && $bookingform->handleRequest($request)->isValid()) {
            if (count($tickets) == 0) {
                $request->getSession()->getFlashBag()->add('fail', 'Votre panier est vide');

                return $this->redirectToRoute('oc_booking_homepage');
            }
            $session = $request->getSession();
            $generatorService = $this->container->get('oc_generator.number');

            $numberbooking = $generatorService->generateAction();

            $booking->setNumberBooking($numberbooking);
            $session->set('booking', $booking);

            return $this->redirectToRoute('oc_booking_confirm');

        }
        return $this->render('OCBookingBundle:Ticket:basket.html.twig', array(
            "tickets" => $tickets,
            "total" => $total,
            "booking" => $bookingform->createView(),
        ));

    }


    // Action de suppresion de ticket.

    public function deleteAction(Request $request, $sessionId)
    {
        $session = $request->getSession();
        $tickets = $session->get('tickets');
        foreach ($tickets as $key => $ticket) {
            if ($ticket->sessionId == $sessionId) {
                unset($tickets[$key]);
                $this->get('session')->getFlashBag()->add('success', 'Ticket supprimé avec succès !');
            }
        }
        $session->set('tickets', $tickets);
        return $this->redirectToRoute('oc_booking_homepage');
    }

    public function paymentAction(Request $request)
    {
        $session = $request->getSession();
        $tickets = $session->get('tickets');
        $priceService = $this->container->get('oc_booking.prices');
        $total = $priceService->getTotal($tickets, $this->getParameter('prices'));

        return $this->render('OCBookingBundle:Ticket:payment.html.twig', array(
            'total' => $total,
            'tickets' => $tickets,

        ));
    }
}

