<?php

namespace App\Controller;


use App\Entity\User;
use Symfony\Component\Mime\Email;
use App\Form\RegistrationFormType;
use App\Security\AppCustomAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mailer\Transport\TransportInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;

class RegistrationController extends AbstractController
{


    #[Route(path: '/registre', name: 'app_register')]
    public function register( TransportInterface $mailer, Request $request, UserPasswordHasherInterface $userPasswordHasher, UserAuthenticatorInterface $userAuthenticator, AppCustomAuthenticator $authenticator, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $contact=$form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );
            $userRoles = $form->get('roles')->getData();
            $user->setRoles($userRoles);
            $entityManager->persist($user);
            $entityManager->flush();
            $userEmail = $form->get('email')->getData();
            $user->setEmail($userEmail);
            $email= (new TemplatedEmail())
            ->from('mohamedjmai811s@gmail.com')
            ->to($user->getEmail())
            ->subject('Contact au sujet du séance de coaching')
            //->htmlTemplate('user/form_patient.html.twig')
            ->text("Inscription réussie")
            ->context([
                'mail'=> $contact->get('email')->getData(),
            ]);
            $mailer->Send($email);
            $this->addFlash("success", "Inscription réussie !");
           
            // do anything else you need here, like send an email

            return $userAuthenticator->authenticateUser(
                $user,
                $authenticator,
                $request
            );
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
}
