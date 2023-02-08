<?php

namespace App\Form;

use App\Entity\Address;
use App\Entity\Carrier;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OrderType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        //dd($options); je trouve le user connectee 
        $user = $options['user'];
        $builder
            ->add('addresses',EntityType::class,[
                'label' => false,
                'required' => true,
                'class' => Address::class,
                'choices' => $user->getAddresses(),
                'multiple' => false,
                'expanded' => true,
            ])
            ->add('carriers',EntityType::class,[
                'label' => 'choisissez votre transporteur  ',
                'required' => true,
                'class' => Carrier::class,
                'multiple' => false,
                'expanded' => true,
            ])
            ->add('submit',SubmitType::class,[
                'label' => 'Validr ma commande',
                'attr' => [
                    'class' => 'btn btn-success btn-block'

                ]

            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'user' => array()
        ]);
    }
}
