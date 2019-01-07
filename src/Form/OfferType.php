<?php

namespace App\Form;

use App\Entity\Offer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichImageType;

class OfferType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('pictureFile', VichImageType::class, [
                'allow_delete' => false,
                'label' => 'Image de votre produit *',
                'download_uri' => false,
                'image_uri' => false,
            ])
            ->add('weight', NumberType::class, [
                'scale' => 2,
                'label' => 'Poids *',
                'attr' => [
                'placeholder' => 'en kg']
            ])
            ->add('start', DateTimeType::class, [
                'label' => 'Disponible à partir de *',
                'widget' => 'single_text',
                'invalid_message' => 'La date doit être 
                de la forme AAAA-MM-JJ HH:MM',
                'help' => 'ex:2019-01-31 20:30',
                'attr' => [
                    'placeholder' => '2019-01-31 20:30']
            ])
            ->add('end', DateTimeType::class, [
                'label' => 'Jusqu\'à *',
                'widget' => 'single_text',
                'invalid_message' => 'La date doit être 
                de la forme AAAA-MM-JJ HH:MM',
                'help' => 'ex:2019-12-31 20:30',
                'attr' => [
                    'placeholder' => '2019-12-31 20:30']
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description',
                'required' => false,
                'attr' => [
                    'placeholder' => 'Décrivez votre produit. Ex : Baguettes, Boites de conserves, ...']
            ])
            ->add('complementary', TextareaType::class, [
                'label' => 'Information complémentaire',
                'required'=> false
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Offer::class,
            'help' => '* champs obligatoires',
        ]);
    }
}
