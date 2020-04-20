<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Region;
use App\Entity\Departement;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class UserFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        if($options['inscription'] == true)
        {
        $builder

        
            ->add('email')
            
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'Les mots de passe ne sont pas identiques, veuillez recommencer.',
                'first_name' => 'first',
                'second_name' => 'second',
                'first_options'  => ['label' => 'Mot de passe'],
                'second_options' => ['label' => 'Confirmation du mot de passe'],
                'required' => true,
                'options' => ['attr' => ['class' => 'password-field']],
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez entrer un mot de passe',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Votre mot de passe doit contenir au minimum 6 caractères',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
            ])

            ->add('nom')
            ->add('prenom')
            ->add('adresse')
            ->add('codePostal')
            ->add('ville')
            ->add('telephone')

            ->add('agreeTerms', CheckboxType::class, [
                'mapped' => false,
                'label' => 'En cochant cette case, j\'accepte les termes du contrat',
                'constraints' => [
                    new IsTrue([
                        'message' => 'Vous devez cocher cette case pour vous inscrire',
                    ]),
                ],
            ])

            ->add('region', EntityType::class, [
                'class' => Region::class,
            ])
        ;







        
    }
    elseif($options['profil'] == true)
    {
        $builder

        
            ->add('email')
            ->add('nom')
            ->add('prenom')
            ->add('adresse')
            ->add('codePostal')
            ->add('ville')
            ->add('telephone')
            ->add('region', EntityType::class, [
                'class' => Region::class,
            ])

        ;

    }

    $formModifier = function (FormInterface $form, Region $region = null)
    {
        $departement = null === $region ? [] : $region->getDepartements();

        $form->add('departement', EntityType::class, [
                'class' => Departement::class,
                'placeholder' => '',
                'choices' => $departement,
                'required' => false
                
        ]);
    };

    $builder->addEventListener(
        FormEvents::PRE_SET_DATA,
        function (FormEvent $event) use ($formModifier) 
        {
            $data = $event->getData();
            $formModifier($event->getForm(), $data->getRegion());
        }
    );

    $builder->get('region')->addEventListener(
        FormEvents::POST_SUBMIT,
        function (FormEvent $event) use ($formModifier) 
        {
            $region = $event->getForm()->getData();
            $formModifier($event->getForm()->getParent(), $region);
            //dump($event);
        }
    );

    }

    

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'inscription' => false,
            'profil' => false
        ]);
    }

    
}
