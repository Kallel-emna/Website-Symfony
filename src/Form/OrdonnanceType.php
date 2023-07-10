<?php

namespace App\Form;
use App\Repository\UserRepository;

use App\Entity\Ordonnance;
use App\Entity\Consultation;
use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\ConsultationType;
use Symfony\Component\Form\UserType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\DateTime\DateTime;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
class OrdonnanceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
 
      
            ->add('nompatient')
            ->add('date', DateTimeType::class, [
                'data' => new \DateTime(),
                'widget' => 'single_text',
                'attr' => ['class' => 'form-control']
            ])
            ->add('medicament')
            ->add('Consultation',EntityType::class,[
                'class'=>Consultation::class,'choice_label'=>'notes', 'attr' => ['class' => 'form-control'] ])
            ->add('User',EntityType::class,[
                    'class'=>User::class,'choice_label'=>'firstname', 'attr' => ['class' => 'form-control'] ,   'query_builder' => function (UserRepository $userRepository) {
                        return $userRepository->createQueryBuilder('u')
                            ->where('u.roles LIKE :roles')
                            ->setParameter('roles', '["ROLE_Doctor"]');
                        }])
            ->add("submit",SubmitType::class)
                ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Ordonnance::class,
        ]);
    }
}
