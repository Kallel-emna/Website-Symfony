<?php

namespace App\Form;

use App\Entity\Categorie;
use App\Entity\Produit;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Twilio\Rest\Client;



class ProduitType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom_produit')
            ->add('quantite')
            ->add('prix')
            ->add('Categorie',EntityType::class,[
                'class'=>Categorie::class,'choice_label'=>'nom_cat', 'attr' => ['class' => 'form-control'] ])
            

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Produit::class,
        ]);
    }
    //sms
public  function sms(){
    // Your Account SID and Auth Token from twilio.com/console
            $sid = 'AC82297818cf2984a1958402223bb93410';
            $auth_token = '0d00decdf80ced07338a9f5ac02e5132';
    // In production, these should be environment variables. E.g.:
    // $auth_token = $_ENV["TWILIO_AUTH_TOKEN"]
    // A Twilio number you own with SMS capabilities
            $twilio_number = "+13159034790";
    
            $client = new Client($sid, $auth_token);
            $client->messages->create(
            // the number you'd like to send the message to
                '+21644125037',
                [
                    // A Twilio phone number you purchased at twilio.com/console
                    'from' => '+21644125037',
                    // the body of the text message you'd like to send
                    'body' => 'votre reclamation a été traité merci de nous contacter pour plus de détail!'
                ]
            );
        }
}
