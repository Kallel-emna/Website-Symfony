<?php

namespace App\Controller;

use Swift_Mailer;
use Swift_Message;
use App\Entity\User;
use App\Form\UserType;
use App\Form\ResetPasswordType;
use App\Service\TokenGenerator;
use App\Form\ForgotPasswordType;
use Symfony\Component\Mime\Email;
use App\Repository\UserRepository;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mailer\Transport\TransportInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;

class SecurityController extends AbstractController
{
    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
    private $emailSender;

    #[Route(path: '/Forgot', name: 'forgot_password')]
    public function ForgotPassword(Request $request, UserRepository $userRepository, TokenGeneratorInterface  $tokenGenerator,TransportInterface $mailer)
    {
        $form = $this->createForm(ForgotPasswordType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $donnees = $form->getData(); //


            $user = $userRepository->findOneBy(['email' => $donnees]);
            if (!$user) {
                $this->addFlash('danger', 'cette adresse n\'existe pas');
                return $this->redirectToRoute("forgot_password");
            }
            $token = $tokenGenerator->generateToken();

            try {
                $user->setToken($token);
                $entityManger = $this->getDoctrine()->getManager();
                $entityManger->persist($user);
                $entityManger->flush();
            } catch (\Exception $exception) {
                $this->addFlash('warning', 'une erreur est survenue :' . $exception->getMessage());
                return $this->redirectToRoute("app_login");
            }

            $url = $this->generateUrl('app_reset_password', array('token' => $token), UrlGeneratorInterface::ABSOLUTE_URL);

            //BUNDLE MAILER
            $email= (new TemplatedEmail())
                ->from('mohamedjmai811s@gmail.com')
                ->to($user->getEmail())
                ->subject('Mot de password oublié')
                ->html("<p>Bonjour,</p><p>Une demande de réinitialisation de mot de passe a été effectuée. Veuillez cliquer sur le lien suivant : ". $url . "</p>");

            $mailer->Send($email);

            //send mail

            $this->addFlash('message', 'E-mail  de réinitialisation du mp envoyé :');
            //    return $this->redirectToRoute("app_login");



        }

        return $this->render("security/forgot_password.html.twig", ['form' => $form->createView()]);
    }
    #[Route('/resetpassword/{token}', name: 'app_reset_password')]
    public function resetpassword(Request $request, string $token, UserRepository $userRepository, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $user = $this->getDoctrine()->getRepository(User::class)->findOneBy(['Token' => $token]);

        if ($user == null) {
            $this->addFlash('danger', 'TOKEN INCONNU');
            return $this->redirectToRoute("app_login");
        }

        if ($request->isMethod('POST')) {
            $user->setToken(null);

            $user->setPassword($passwordEncoder->encodePassword($user, $request->request->get('password')));
            $entityManger = $this->getDoctrine()->getManager();
            $entityManger->persist($user);
            $entityManger->flush();

            $this->addFlash('message', 'Mot de passe mis à jour :');
            return $this->redirectToRoute("app_login");
        } else {
            return $this->render("security/reset_password.html.twig", ['Token' => $token]);
        }
    }
}
