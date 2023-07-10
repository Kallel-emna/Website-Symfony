<?php

namespace App\Controller;

use App\Entity\Task;
use App\Repository\TaskRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TaskController extends AbstractController
{
    #[Route('/task', name: 'task')]
    public function index(): Response
    {
        return $this->render('task/index.html.twig', [
            'controller_name' => 'StudentController',
        ]);
    }

    #[Route('/liste', name: 'liste')]
    public function showMAction(TaskRepository $repository): JsonResponse
    {
        $task = $repository->findAll();
        return $this->json(
            $task,
            200,
            [],
            [ObjectNormalizer::CIRCULAR_REFERENCE_HANDLER => function () {
                return 'symfony5';
            }]
        );
    }


    #[Route('/add', name: 'add_task')]
    public function addCalendar(Request $req, EntityManagerInterface $em)
    {
        $title = $req->query->get("title");
        $name = $req->query->get("name");
        $status = $req->query->get("status");
        $date = $req->query->get("date");
        $task = new Task();
        $task->setTitle($title);
        $task->setName($name);
        $task->setStatus($status);
        $task->setDate($date);
        try {
            $em = $this->getDoctrine()->getManager();
            $em->persist($task);
            $em->flush();
            return new JsonResponse("Task Created", 200);
        } catch (\Exception $ex) {
            return new Response("Exception", $ex->getMessage());
        }
    }

    #[Route('/liste/delete', name: 'DeleteTask', methods: ['GET', 'POST'])]
     public function deleteAction(Request $request)
     {
         $id = $request->get("id");
         $em = $this->getDoctrine()->getManager();
         $User = $em->getRepository(Task::class)->find($id);
         if ($User != null) {
             $em->remove($User);
             $em->flush();
             $serialize = new Serializer([new ObjectNormalizer()]);
             $formatted = $serialize->normalize("Account deleted");
             return new JsonResponse($formatted);
         }
         return new JsonResponse("id Account invalide.");
     }


     #[Route('/liste/modifier', name: 'modifierTask', methods: ['GET', 'POST'])]
    public function updateAction(TaskRepository $repo, Request $request)
    {

        $em = $this->getDoctrine()->getManager();

        $task = $repo->find($request->get('id'));
        $task->setTitle($request->get('title'));
        $task->setName($request->get('name'));
        $task->setStatus($request->get('status'));
        $task->setDate($request->get('date'));
        $em->persist($task);
        $em->flush();

        return new JsonResponse($task);
    }
}
