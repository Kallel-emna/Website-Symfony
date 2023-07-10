<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Form\searchType;
use App\Form\RegistrationFormType;
use App\Repository\UserRepository;
use App\Security\AppCustomAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;

use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mailer\Transport\TransportInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;

#[Route('/user')]
class UserController extends AbstractController
{

    #[Route('/', name: 'app_user_index', methods: ['GET'])]
    public function index(UserRepository $userRepository,PaginatorInterface $paginator,Request $request): Response
    {
        if ($this->isGranted('ROLE_ADMIN')) {
            $donnees = $userRepository->findAll();
            $users = $paginator->paginate(
                $donnees,
                $request->query->getInt('page',1),
                5
            );
            return $this->render('user/index.html.twig', [
                'users' => $users]);
        } else if ($this->isGranted('ROLE_PATIENT')) {
            return $this->render('user/indexPatient.html.twig', [
                'controller_name' => 'PatientController',
            ]);

        } else if ($this->isGranted('ROLE_Doctor')) {
            return $this->render('user/indexDoctor.html.twig', [
                'controller_name' => 'PatientController',
            ]);
        } else if ($this->isGranted('ROLE_RECEPT')) {
            return $this->render('user/index_Recept.html.twig', [
                'controller_name' => 'PatientController',
            ]);
        } else if ($this->isGranted('ROLE_PHARMACIEN')) {
            return $this->render('user/index_pharmacien.html.twig', [

                'controller_name' => 'PatientController',
            ]);
        } else {

            return $this->render('user/404.html.twig');
        }
    }
   /* #[Route('/listUser', name: 'list_User')]
    public function listUser(Request $request,UserRepository $userRepository)
    {
        $user= $userRepository->findAll();
       $formSearch= $this->createForm(searchType::class);
       $formSearch->handleRequest($request);
       if($formSearch->isSubmitted()){
           $firstname= $formSearch->get('firstname')->getData();
           $result= $userRepository->search($firstname);
           return $this->renderForm("user/index.html.twig",
               array("tabStudent"=>$result,
                    "searchForm"=>$formSearch));
       }
         return $this->renderForm("user/index.html.twig",
           array("tabStudent"=>$user,
                "searchForm"=>$formSearch));
    }*/
    /**
     * @throws TransportExceptionInterface
     */
    #[Route('/new', name: 'app_user_new', methods: ['GET', 'POST'])]
    public function new(Request $request, TransportInterface $mailer, UserPasswordHasherInterface $userPasswordHasher, UserAuthenticatorInterface $userAuthenticator, AppCustomAuthenticator $authenticator, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $contact = $form->handleRequest($request);

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
            ->subject('bienvenue chez Clinimate ')
            //->htmlTemplate('user/form_patient.html.twig')
            ->text("Inscription réussie")
            ->context([
                'mail'=> $contact->get('email')->getData(),
            ]);

            $mailer->Send($email);
            $this->addFlash("success", "Inscription réussie !");
        }

        return $this->render('registration/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }


    #[Route('/{id}', name: 'app_user_show', methods: ['GET'])]
    public function show(User $user): Response
    {
        return $this->render('user/show.html.twig', [
            'user' => $user,
        ]);
    }
    #[Route('/{id}/show', name: 'Patient_profile', methods: ['GET'])]
    public function Profile($id, Security $security): Response
    {
        $user = $this->getDoctrine()->getRepository(User::class)->find($id);
        return $this->render('user/Profile_patient.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_user_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, User $user, UserRepository $userRepository): Response
    {
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userRepository->save($user, true);

            return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('user/edit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }
    #[Route('/{id}/editpatient', name: 'edit_patient', methods: ['GET', 'POST'])]
    public function editpatient(Request $request, User $user, UserRepository $userRepository): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userRepository->save($user, true);

            return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('user/edit_patient.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_user_delete', methods: ['POST'])]
    public function delete(Request $request, User $user, UserRepository $userRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $user->getId(), $request->request->get('_token'))) {
            $userRepository->remove($user, true);
        }

        return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
    }
}
