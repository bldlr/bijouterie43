<?php

namespace App\Form;

use App\Entity\Search;
use App\Entity\Categories;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class SearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

      

        $builder
            ->add('minPrix', IntegerType::class, [
                'required' => false,
                'label' => false,
                'attr' => [
                    'placeholder' => 'Prix minimum'
                ]
            ])

            ->add('maxPrix', IntegerType::class, [
                'required' => false,
                'label' => false,
                'attr' => [
                    'placeholder' => 'Prix maximum'
                ]
            ])


             ->add('categorie', EntityType::class,[
                'class' => Categories::class,
                'label' => false,
                'required' => false,
                'placeholder' => 'Catégorie',
                'choice_label' => 'libelle',
                
            ])


            ->add('genre', ChoiceType::class, [
                'required' => false,
                'label' => false,
                'placeholder' => 'Genre',
                'choices' => [
                    'Homme' => "homme",
                    'Femme' => "femme"
                    ]
            ])






        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Search::class,
            'method' => 'get',
            'csrf_protection' => false,
        ]);
    }

    public function getBlockPrefix()
    {
        return '';
    }



}
