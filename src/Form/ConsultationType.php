<?php

namespace App\Form;

use App\Entity\Consultation;
use App\Entity\Ordonnance;
use App\Entity\RendezVous;
use Symfony\Component\Form\RendezVousType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ConsultationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
 
            ->add('notes')
            ->add('prix')
            ->add('id_RendezVous',EntityType::class,[
                'class'=>RendezVous::class,'choice_label'=>'id', 'attr' => ['class' => 'form-control'] ])
                ->add("submit",SubmitType::class) 
               
                ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Consultation::class,
        ]);
    }
  
}
