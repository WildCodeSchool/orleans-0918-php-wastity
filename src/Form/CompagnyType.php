<?php

namespace App\Form;

use App\Entity\Compagny;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CompagnyType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('type', TextType::class,
                array('label' => 'Type', 'attr' =>['placeholder' => 'Type de votre entreprise','class' => 'form-control']))
            ->add('name', TextType::class,
                array('label' => 'Nom', 'attr' =>['placeholder' => 'Votre entreprise', 'class' => 'form-control']))
            ->add('adress', TextType::class,
                array('label' => 'Adresse', 'attr' =>['placeholder' => '1 rue de votre entreprise', 'class' => 'form-control']))
            ->add('postalCode', TextType::class,
                array('label' => 'Code Postal', 'attr' =>['placeholder' => '45000', 'class' => 'form-control']))
            ->add('city', TextType::class,
                array('label' => 'Ville', 'attr' =>['placeholder' => 'Orléans', 'class' => 'form-control']))
            ->add('phone', TextType::class,
                array('label' => 'Téléphone', 'attr' =>['placeholder' => '0202030303', 'class' => 'form-control']))
            ->add('email', EmailType::class,
                array('label' => 'Email', 'attr' =>['placeholder' => 'votreassociation@email.com', 'class' => 'form-control']))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Compagny::class,
        ]);
    }
}
