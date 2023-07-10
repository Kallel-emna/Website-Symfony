<?php

namespace App\Form;
use App\Repository\UserRepository;

use App\Entity\RendezVous;
use App\Entity\Consultation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\UserType;
use App\Entity\User;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\ConsultationType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\DateTime\DateTime;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
class RendezVousType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
          
            ->add('time')
            ->add('date_rendezvous', DateTimeType::class, [
                'data' => new \DateTime(),
                'widget' => 'single_text',
                'attr' => ['class' => 'form-control']
            ])
            ->add('User',EntityType::class,[
                'class'=>User::class,'choice_label'=>'email', 'attr' => ['class' => 'form-control'],
                'query_builder' => function (UserRepository $userRepository) {
                    return $userRepository->createQueryBuilder('u')
                        ->where('u.roles LIKE :roles')
                        ->setParameter('roles', '["ROLE_PATIENT"]');
                    }
            ] )
              ->add('Confirmation', CheckboxType::class, [
        'required' => false,
        'label' => 'Confirmation',
    ])  
            ->add("submit",SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => RendezVous::class,
        ]);
    }
    
    
}
