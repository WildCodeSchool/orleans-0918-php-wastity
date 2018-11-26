<?php

namespace App\Form;

use App\Entity\Offer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OfferType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('weight', IntegerType::class, [
                'label' => 'Poids estimé en kg'
            ])
            ->add('start', DateTimeType::class, [
                'label' => 'Disponible à partir de',
                'widget' => 'single_text',
            ])
            ->add('end', DateTimeType::class, [
                'label' => 'Jusqu\'à',
                'widget' => 'single_text'
            ])
            ->add('description', TextareaType::class)
            ->add('complementary', TextareaType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Offer::class,
        ]);
    }
}
