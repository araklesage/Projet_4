<?php
// src/OC/BookingBundle/Controller/BookingController.php

namespace OC\BookingBundle\Controller;

use OC\BookingBundle\Entity\Booking;
use OC\BookingBundle\Entity\Contact;
use OC\BookingBundle\Entity\Customer;
use OC\BookingBundle\Entity\Ticket;
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
    public function indexAction(Request $request)
    {
        $ticket = new Ticket();
        $form = $this->get('form.factory')->create(TicketType::class, $ticket);

        if ($request->isMethod('POST')){
            $form->handleRequest($request);

            if ($form->isValid()){
                $em = $this->getDoctrine()->getManager();
                $session= $request->getSession();
                $tickets= ($session->get('tickets'))?$session->get('tickets'):array();
                $ticket->sessionId = uniqid();
                $tickets[]= $ticket;
                $session->set('tickets',$tickets);

                $request->getSession()->getFlashBag()->add('success', 'Ticket ajouté');

                return $this->render('OCBookingBundle:Ticket:index.html.twig', array(
                    "form"=>$form->createView(),
                    "tickets"=>$tickets,
                ));
            }
        }

            return $this->render('OCBookingBundle:Ticket:index.html.twig', array(
                "form"=>$form->createView(),
        ));
    }

    public function menuAction()
    {


        return $this->render('basket.html.twig');
    }



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

    public function customerAction()
    {
        $customer = new Customer();

        $card  = $this->get('form.factory')->create(CustomerType::class, $customer);

        return $this->render('OCBookingBundle:Ticket:card.html.twig', array(
            'card' =>$card->createView(),
        ));
    }

    public function panierAction(Request $request)
    {
        $priceService = $this -> container ->get('oc_booking.prices');
        $session= $request->getSession();
        if ($session->get('tickets')) {
            $tickets = $session->get('tickets');
            if (count($tickets) > 0) {
                foreach ($tickets as $ticket){
                    $ticket->price =$priceService->priceAction($ticket->getBirthDate(), $this->getParameter('prices'));
                }
            }
        } else {
            $tickets = array();
        }

        return $this->render('OCBookingBundle:Ticket:basket.html.twig', array(
            "tickets"=>$tickets,
        ));
    }


    public function getPrixTotal()
    {
        $price = 0;
        foreach($this->getTicketsList()as $ticket) {
            $price += $ticket->getTicketsLists();
        }

        return $price;
    }

    public function mailAction(Request $request)
    {

        $message = (new \Swift_Message('Validation de réservation'))
            ->setFrom('estebangrabette@gmail.com')
            ->setTo('estebangrabette@gmail.com')
            ->setBody(
                $this->renderView(
                    'OCBookingBundle:Emails:validation.html.twig',
                    array()
                ),
                'text/html'
            )

        ;

        $this->get('mailer')->send($message);

        $request->getSession()->getFlashbag()->add('success', 'Commande validée');

        $url = $this->get('router')->generate('oc_booking_homepage');

        var_dump($message);

        return new RedirectResponse($url);




    }

    public function deleteAction(Request $request, $sessionId){
        $session = $request->getSession();
        $tickets = $session->get('tickets');

        ;        foreach ($tickets as $key => $ticket){
            if ( $ticket->sessionId==$sessionId ) {
                unset($tickets[$key]);
                $this->get('session')->getFlashBag()->add('success','Ticket supprimé avec succès !');
            }
        }
        $session->set('tickets',$tickets);
        return $this->redirectToRoute('oc_booking_homepage');
    }
}
