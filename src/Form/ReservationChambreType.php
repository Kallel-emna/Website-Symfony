<?php

namespace App\Form;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

use App\Entity\User;
use App\Entity\Chambre;
use App\Entity\ReservationChambre;
use App\Repository\UserRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;


class ReservationChambreType extends AbstractType
{
    
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        $builder
        ->add('date_admission', DateType::class, [
            'widget' => 'single_text',
            'required' => true,
            'constraints' => [
                new Callback([$this, 'validateDates'])
            ]
        ])
        ->add('date_sortie', DateType::class, [
            'widget' => 'single_text',
            'required' => false,
            'constraints' => [
                new Callback([$this, 'validateDates'])
            ]
        ])
            ->add('chambre',EntityType::class,[
                'class'=>Chambre::class,'choice_label'=>'id', 'attr' => ['class' => 'form-control'] ])
            ->add('patient', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'lastname',
                'attr' => ['class' => 'form-control'],
                'query_builder' => function (UserRepository $userRepository) {
                    return $userRepository->createQueryBuilder('u')
                        ->where('u.roles LIKE :roles')
                        ->setParameter('roles', '["ROLE_PATIENT"]');
                    }
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ReservationChambre::class,
        ]);
    }
    public function validateDates($data, ExecutionContextInterface $context)
    {
        $date_admission = $context->getRoot()->get('date_admission')->getData();
        $date_sortie = $context->getRoot()->get('date_sortie')->getData();

        if ($date_admission && $date_sortie && $date_admission > $date_sortie) {
            $context->buildViolation('La date d\'admission doit être antérieure à la date de sortie.')
                ->atPath('date_admission')
                ->addViolation();
        }
    }
}
