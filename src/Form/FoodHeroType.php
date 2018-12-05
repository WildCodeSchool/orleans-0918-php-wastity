<?php

namespace App\Form;

use App\Entity\FoodHero;
use App\Entity\User;
use function Sodium\add;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FoodHeroType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('profileImageFile', FileType::class, [
                'label' => 'Votre Image de Profil',
                'attr' => [
                    'class' => false,
                ]
                ])
            ->add('phone', TelType::class, [
                'label' => 'Téléphone',
                'attr' => [
                    'placeholder' => '0202030303',
                ]]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => FoodHero::class,
        ]);
    }
}
