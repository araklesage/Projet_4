<?php

namespace OC\BookingBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\DateTime;

class CustomerType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('cardName',       TextType::class)
            ->add('cardNumber',     NumberType::class)
            ->add('cvc',            NumberType::class)
            ->add('expirationDateMonth', ChoiceType::class, array(
                'choices'=> array(
                    'Janvier' => '1',
                    'Fevrier' => '2',
                    'Mars' => '3',
                    'Avril' => '4',
                    'Mai' => '5',
                    'Juin'=> '6',
                    'Juillet' => '7',
                    'Août' => '8',
                    'Septembre' => '9',
                    'Octobre' => '10',
                    'Novembre' => '11',
                    'Décembre' => '12',
                )))
            ->add('expirationDateYear', ChoiceType::class, array(
                'choices'=> array(
                    '2011' => '11',
                    '2012' => '12',
                    '2013' => '13',
                )));
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'OC\BookingBundle\Entity\Customer'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'oc_bookingbundle_customer';
    }


}
