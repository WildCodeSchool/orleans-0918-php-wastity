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
                'required' => false,
                'allow_delete' => false,
                'label' => 'Image de votre produit',
                'download_uri' => false,
                'image_uri' => false,
            ])
            ->add('weight', NumberType::class, [
                'label' => 'Poids',
                'help' => 'estimé en kg'
            ])
            ->add('start', DateTimeType::class, [
                'label' => 'Disponible à partir de',
                'widget' => 'single_text',
                'invalid_message' => 'La date doit être 
                de la forme AAAA-MM-JJ HH:MM',
            ])
            ->add('end', DateTimeType::class, [
                'label' => 'Jusqu\'à',
                'widget' => 'single_text',
                'invalid_message' => 'La date doit être 
                de la forme AAAA-MM-JJ HH:MM',
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description'
            ])
            ->add('complementary', TextareaType::class, [
                'label' => 'Information complémantaire'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Offer::class,
        ]);
    }
}
