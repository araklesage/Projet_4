<?php
// src/OC/BookingBundle/Controller/BookingController.php

namespace OC\BookingBundle\Controller;

use OC\BookingBundle\Entity\Booking;
use OC\BookingBundle\Entity\Customer;
use OC\BookingBundle\Entity\Ticket;
use OC\BookingBundle\Utils\PriceService;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
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

        $form = $this->get('form.factory')->createBuilder(FormType::class, $ticket)
            ->add('date',       DateType::class)
            ->add('firstName',  TextType::class)
            ->add('lastName',   TextType::class)
            ->add('birthDate',  BirthdayType::class)
            ->add('country',    CountryType::class)
            ->add('reduct',     CheckboxType::class, array('required' => false))
            ->add('save',       SubmitType::class)

            ->getForm()
        ;

        if ($request->isMethod('POST')){
            $form->handleRequest($request);

            if ($form->isValid()){
                $em = $this->getDoctrine()->getManager();
                $session= $request->getSession();
                $tickets= ($session->get('tickets'))?$session->get('tickets'):array();
                $tickets[]= $ticket;
                $session->set('tickets',$tickets);

                $request->getSession()->getFlashBag()->add('notice', 'Ticket ajouté');

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
    public function panierAction(Request $request)
    {
        $priceService = $this -> container ->get('oc_booking.prices');
        $session= $request->getSession();
        $tickets = $session->get('tickets');




        return $this->render('OCBookingBundle:Ticket:basket.html.twig', array(
            "tickets"=>$tickets,
        ));
    }
    public function addTicketAction()
        {

        $ticket = new Ticket();

        $formBuilder = $this->get('form.factory')->createBuilder(FormType::class, $ticket);

        $formBuilder
            ->add('date',       DateType::class)
            ->add('firstName',  TextType::class)
            ->add('lastName',   TextType::class)
            ->add('birthDate',  DateType::class)
            ->add('reduct',     CheckboxType::class)
            ->add('save',       SubmitType::class)
            ;

        $form = $formBuilder->getForm();

        return $this->render('OCBookingBundle:Ticket:add.html.twig', array(
            'form' =>$form->createView(),
        ));
        }

    public function customerForm()
    {
        $customer = new Customer();

        $formBuilder = $this->get('form.factory')->createBuilder(FormType::class, $customer);

        $formBuilder
            ->add('cardName',   TextType::class)
            ->add('cardNumber', IntegerType::class)
            ->add('cvv',        IntegerType::class);
    }

    public function menuAction()
    {


        return $this->render('basket.html.twig');
    }


    public function getPrixTotal()
    {
        $price = 0;
        foreach($this->getTicketsList()as $ticket) {
            $price += $ticket->getTicketsLists();
        }

        return $price;
    }

    public function mailAction()
    {
        $message = (new \Swift_Message('Hello Email'))
            ->setFrom('send@example.com')
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



        return $this->render('OCBookingBundle:Ticket:index.html.twig');
    }



}
