<?php

namespace App\Controller;
use App\Entity\RendezVous;
use App\Form\RdvpType;
use App\Repository\RendezVousRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class RdvpController extends AbstractController
{
    #[Route('/new1', name: 'app_rendez_vous_new1', methods: ['GET', 'POST'])]
    public function new(Request $request, RendezVousRepository $rendezVousRepository): Response
    {
        $rendezVou = new RendezVous();
        $form = $this->createForm(RdvpType::class, $rendezVou);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
    
            $rendezVousRepository->save($rendezVou, true);

            return $this->redirectToRoute('app_front', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('chtioui/RDV.html.twig', [
            'rendez_vou' => $rendezVou,
            'form' => $form,
        ]);
    }
    #[Route('/index1', name: 'app_rendez_vous_index1', methods: ['GET'])]
    public function index1(RendezVousRepository $rendezVousRepository): Response
    {
        return $this->render('chtioui/index.html.twig', [
            'rendez_vouses' => $rendezVousRepository->findAll(),
        ]);
    }
}
