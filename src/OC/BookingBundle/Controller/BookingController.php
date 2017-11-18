<?php
// src/OC/BookingBundle/Controller/BookingController.php

namespace OC\BookingBundle\Controller;

use OC\BookingBundle\Entity\Customer;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
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
    public function indexAction($page)
    {
        if ($page < 1) {
            throw new NotFoundHttpException('Page "'.$page.'"inexistante.');
        }

        //liste ticket en dur

        $listTickets = array(
            array(
                array(
                    'title'   => 'Ticket jour plein tarif',
                    'id'      => 1,
                    'price'  => '16 €',
                    'content' => "permet l'accès à l'ensemble du musée toute la journée. ",
                    'date'    => new \Datetime()),
                array(
                    'title'   => 'Ticket jour enfant',
                    'id'      => 2,
                    'price'  => '8 €',
                    'content' => "permet l'accès à l'ensemble du musée toute la journée, pour les enfants de moins de 12 ans. ",
                    'date'    => new \Datetime()),
                array(
                    'title'   => 'Ticket jour senior',
                    'id'      => 3,
                    'price'  => '12 €',
                    'content' => "permet l'accès à l'ensemble du musée toute la journée, pour les personnes de plus de 60 ans. ",
                    'date'    => new \Datetime()),
                array(
                    'title'   => 'Ticket jour tarif réduit',
                    'id'      => 4,
                    'price'  => '10 €',
                    'content' => "permet l'accès à l'ensemble du musée toute la journée, pour les étudiants.",
                    'date'    => new \Datetime()),
            ));

            return $this->render('OCBookingBundle:Ticket:index.html.twig', array(
            'listTickets' => $listTickets,
        ));
    }

    public function menuAction()
    {


        return $this->render('OCBookingBundle:Ticket:menu.html.twig');
    }
    /*public function buildForm()
    {
        $customer = new Customer();

        $formbuilder = $this ->get('form.factory')->createBuilder(FormType::class, $advert);

        $formbuilder
            ->add('firstName',     TextType::class)
            ->add('lastName',      TextType::class)
            ->add('country',        CountryType::class)
            ->add('birthDate',      BirthdayType::class)

        $form = $formBuilder->getForm();

         return $this->render('OCBookingBundle:Ticket:form.html.twig', array(
             'form' => $form->(),
         ));
    }
    */

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
