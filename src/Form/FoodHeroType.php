<?php

namespace App\Form;

use App\Entity\FoodHero;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichImageType;

class FoodHeroType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('profileImageFile', VichImageType::class, [
                'required' => false,
                'allow_delete' => false,
                'label' => 'Votre image de profil',
                'download_uri' => false,
                'image_uri' => false,
                'require' => true
            ])
            ->add('phone', TelType::class, [
                'label' => 'Téléphone',
                'require' => true,
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
