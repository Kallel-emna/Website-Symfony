<?php

namespace App\Form;

use App\Entity\DossierMedical;
use App\Entity\Ordonnance;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class DossierType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('Certificat')
            ->add('GroupeSanguin')
            ->add('Ordonnance',EntityType::class,[
                'class'=>Ordonnance::class,'choice_label'=>'id', 'attr' => ['class' => 'form-control'] ])
            ->add("Submit",SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => DossierMedical::class,
        ]);
    }
    
}
