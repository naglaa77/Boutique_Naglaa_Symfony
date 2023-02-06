<?php

namespace App\Form;

use App\Entity\Address;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
class AddressType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name',TextType::class,[
                'label' => 'Quel nom souhaitez donner a votre adress',
                'attr' => [
                    'placeholder' => 'Nommez votre adresse'
                ]
            ])
            ->add('firstname',TextType::class,[
                'label' => 'Votre prenom ',
                'attr' => [
                    'placeholder' => 'Entrez votre prenom'
                ]
            ])
            ->add('lastname',TextType::class,[
                'label' => 'Votre nom',
                'attr' => [
                    'placeholder' => 'Entrez votre nom'
                ]
            ])
            ->add('company',TextType::class,[
                'label' => 'Votre société',
                'required' => false,
                'attr' => [
                    'placeholder' => 'Entrez votre société'
                ]
            ])
            ->add('address',TextType::class,[
                'label' => 'Votre adress',
                'attr' => [
                    'placeholder' => 'Entrez votre adresse'
                ]
            ])
            ->add('postal',TextType::class,[
                'label' => ' Votre postal',
                'attr' => [
                    'placeholder' => 'Entrez votre postal'
                ]
            ])
            ->add('city',TextType::class,[
                'label' => 'Votre ville',
                'attr' => [
                    'placeholder' => 'Entrez  votre ville'
                ]
            ])
            ->add('country',CountryType::class,[
                'label' => 'Votre paye',
                'attr' => [
                    'placeholder' => 'Entrez  votre paye'
                ]
            ])
            ->add('phone',TelType::class,[
                'label' => ' Votre portable',
                'attr' => [
                    'placeholder' => 'Entrezvotre portable'
                ]
            ])
            ->add('submit', SubmitType::class,[
                'label' => 'valider',
                'attr' => [
                    'class' => 'btn-block btn-info'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Address::class,
        ]);
    }
}
