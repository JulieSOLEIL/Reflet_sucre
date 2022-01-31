<?php

namespace App\Form;

use App\Entity\Address;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;

class AddressType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Quel nom souhaitez-vous donner a votre adresse ?',
                'attr' => [
                    'placeholder' => 'Saisir le nom de l\'adresse'
                ]
            ])
            ->add('firstname', TextType::class, [
                'label' => 'Prenom',
                'attr' => [
                    'placeholder' => 'Saisir votre prenom'
                ]
            ])
            ->add('lastname', TextType::class, [
                'label' => 'Nom de famille',
                'attr' => [
                    'placeholder' => 'Saisir votre nom de famille'
                ]
            ])
            ->add('company', TextType::class, [
                'label' => 'Societe (facultatif)',
                'required' => false,
                'attr' => [
                    'placeholder' => 'Saisir le nom de votre societe (facultatif)'
                ]
            ])
            ->add('address', TextType::class, [
                'label' => 'Adresse',
                'attr' => [
                    'placeholder' => 'Saisir votre adresse'
                ]    
            ])
            ->add('postal', TextType::class, [
                'label' => 'Code postal',
                'attr' => [
                    'placeholder' => 'Saisir votre code postal'
                ]    
            ])
            ->add('city', TextType::class, [
                'label' => 'Ville',
                'attr' => [
                    'placeholder' => 'Saisir le nom de la ville'
                ]
            ])
            ->add('country', CountryType::class, [
                'label' => 'Pays',
                'attr' => [
                    'placeholder' => 'Saisir le nom du pays'
                ]    
            ])
            ->add('phone', TextType::class, [
                'label' => 'Numero de telephone',
                'attr' => [
                    'placeholder' => 'Saisir votre numero de telephone'
                ]
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Valider',
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
