<?php

namespace App\Form;

use App\Entity\Schedule;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ScheduleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder

            ->add('openingAM', TimeType::class, [
                'widget' => 'single_text'
            ])
            ->add('closingAM', TimeType::class, [
                'widget' => 'single_text'
            ])
            ->add('openingPM', TimeType::class, [
                'widget' => 'single_text'
            ])
            ->add('closingPM', TimeType::class, [
                'widget' => 'single_text'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Schedule::class,
        ]);
    }
}
