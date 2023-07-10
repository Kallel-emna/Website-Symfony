<?php

namespace App\Form;

use App\Entity\BilanMedical;
use App\Entity\DossierMedical;
use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType; 
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\HttpFoundation\File\File;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Vich\UploaderBundle\Form\Type\VichImageType;




class BilanType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        
            ->add('Antecedents')
            ->add('Taille')
            ->add('Poids')
            ->add('ExamensBiologiques')
           ->add('DossierMedical',EntityType::class,[
                'class'=>DossierMedical::class,'choice_label'=>'id', 'attr' => ['class' => 'form-control'] ])
            ->add('User',EntityType::class,[
                    'class'=>User::class,'choice_label'=>'id', 'attr' => ['class' => 'form-control'] ])
                    
            ->add('Valider', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => BilanMedical::class,
        ]);
    }
}

/*
String antecedents = b.getAntecedents();
String taille =  b.getTaille();
String poid = b.getPoids();
String examens = b.getExamensBiologiques();
String image = b.getImagerieMedicale(); */