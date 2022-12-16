<?php
namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Classe\Searche;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Entity\Category;
class SearcheType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('string', TextType::class,[
                'label' => false,
                'required' => false,
                'attr' =>[
                    'placeholder' => 'Votre recherche ...'
                ]
            
            ])
            ->add('categories',EntityType::class,[
                'label' => false,
                'required' => false,
                'class' =>Category::class,
                'multiple' => true,
                'expanded' => true,
            ])

        ;
    
    }
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Searche::class,
            'method'  => 'GET',
            'crsf_protection' => false,
            
        ]);
    }

    public function getBlockPrefix()
    {
        return '';

    }

}