<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class ServiceUserController extends AbstractController
{
    #[Route('User/signup', name: 'app_service_user')]
    public function signupAction(Request $request, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $email = $request->query->get("email");
        $roles = $request->query->get("roles");
        $password = $request->query->get("password");
        $firstname = $request->query->get("firstname");
        $lastname = $request->query->get("lastname");
        $address = $request->query->get("address");
        $telephone = $request->query->get("telephone");
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return new response("email invalid");
        }
        $user  = new User();
        $user->setEmail($email);
        $user->setRoles(array($roles));
        $user->setPassword(
            $passwordEncoder->encodePassword($user, $password)
        );
        $user->setFirstname($firstname);
        $user->setLastname($lastname);
        $user->setAddress($address);
        $user->setTelephone($telephone);
        try {
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
            return new JsonResponse("account created", 200);
        } catch (\Exception $ex) {
            return new Response("exception" . $ex->getMessage());
        }
    }
    #[Route('User/signin', name: 'app_service_userIn')]
    public function signinAction(Request $request): Response
    {
        $email = $request->query->get("email");
        $password = $request->query->get("password");
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository(User::class)->findOneBy(['email' => $email]);
        if ($user) {
            if (password_verify($password, $user->getPassword())) {
                $serializer = new Serializer([new ObjectNormalizer()]);
                $formatted = $serializer->normalize($user);
                return new JsonResponse($formatted);
            }
            else {return new Response("password incorrect");}

        }
        else {return new Response("account not found");}
        
    }
    #[Route('User/shows', name: 'app_service_ShowUser')]

    public function showMAction(UserRepository $repository): JsonResponse
    {
        $user = $repository->findAll();
        return $this->json(
            $user,
            200,
            [],
            [ObjectNormalizer::CIRCULAR_REFERENCE_HANDLER => function () {
                return 'symfony5';
            }]
        );
    }
    #[Route('/User/modifier', name: 'modifM', methods: ['GET', 'POST'])]
    public function updateAction(UserRepository $repo, Request $request)
    {

        $em = $this->getDoctrine()->getManager();

        $user = $repo->find($request->get('id'));
        $user->setEmail($request->get('email'));
        $user->setFirstname($request->get('firstname'));
        $user->setLastname($request->get('lastname'));
        $user->setAddress($request->get('address'));
        $user->setTelephone($request->get('telephone'));
        $em->persist($user);
        $em->flush();

        return new JsonResponse($user);
    }
   
    #[Route('/Userr/delete', name: 'defM', methods: ['GET', 'POST'])]
     public function deleteAction(Request $request)
     {
         $id = $request->get("id");
         $em = $this->getDoctrine()->getManager();
         $User = $em->getRepository(User::class)->find($id);
         if ($User != null) {
             $em->remove($User);
             $em->flush();
             $serialize = new Serializer([new ObjectNormalizer()]);
             $formatted = $serialize->normalize("account delete");
             return new JsonResponse($formatted);
         }
         return new JsonResponse("id Account invalide.");
     }
}

