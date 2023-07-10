<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\UserType;
use App\Entity\Calendar;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ColorType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

class CongeType extends AbstractType
{


    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Calendar::class,
            'background_color' => '#00FF00',
            'border_color' => '#000000',
            'text_color' => '#ffffff',
        ]);
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title')
            ->add('start', DateTimeType::class, [
                'date_widget' => 'single_text'
            ])
            ->add('end', DateTimeType::class, [
                'date_widget' => 'single_text'
            ])
            ->add('description')
            ->add('all_day')
            ->add('background_color', ColorType::class, [
                'data' => $options['background_color'],
                'disabled' =>true,
            ])
            ->add('border_color', ColorType::class, [
                'data' => $options['border_color'],
                'required' => true,
                'disabled' =>true,
            ])
            ->add('text_color', ColorType::class, [
                'data' => $options['text_color'],
                'required' => true,
                'disabled' =>true,
            ]);
    }

    
}
