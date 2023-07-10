<?php

namespace App\Controller;

use App\Entity\Chambre;
use App\Form\ChambreType;
use Symfony\Component\Serializer\Serializer;
use App\Repository\ChambreRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Doctrine\ORM\EntityManagerInterface;


#[Route('/chambre')]
class ChambreController extends AbstractController
{
    #[Route('/', name: 'app_chambre_index', methods: ['GET'])]
    public function index(ChambreRepository $chambreRepository): Response
    {
        return $this->render('chambre/index.html.twig', [
            'chambres' => $chambreRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_chambre_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ChambreRepository $chambreRepository): Response
    {
        $chambre = new Chambre();
        $form = $this->createForm(ChambreType::class, $chambre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $chambreRepository->save($chambre, true);

            return $this->redirectToRoute('app_chambre_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('chambre/new.html.twig', [
            'chambre' => $chambre,
            'form' => $form,
        ]);
    }
    
    #[Route('/{id}', name: 'app_chambre_show', methods: ['GET'])]
    public function show(Chambre $chambre): Response
    {
        
        $users = [];
        foreach ($chambre->getReservationChambres() as $reservationChambre) {
            if($reservationChambre->getDateSortie()==NULL&&$reservationChambre->getDateAdmission() <=new \DateTime())
            $users[] = $reservationChambre->getUser();

        }
        $prochain = [];
        foreach ($chambre->getReservationChambres() as $reservationChambre) {
            if($reservationChambre->getDateAdmission() > new \DateTime() &&$reservationChambre->getDateSortie()==NULL)
            $prochain[] = $reservationChambre->getUser();

        }
        return $this->render('chambre/show.html.twig', [
            'chambre' => $chambre,
            'users' => $users,
            'prochain'=>$prochain
        ]);
    }
    

    #[Route('/{id}/edit', name: 'app_chambre_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Chambre $chambre, ChambreRepository $chambreRepository): Response
    {
        $form = $this->createForm(ChambreType::class, $chambre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $chambreRepository->save($chambre, true);

            return $this->redirectToRoute('app_chambre_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('chambre/edit.html.twig', [
            'chambre' => $chambre,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_chambre_delete', methods: ['POST'])]
    public function delete(Request $request, Chambre $chambre, ChambreRepository $chambreRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$chambre->getId(), $request->request->get('_token'))) {
            $chambreRepository->remove($chambre, true);
        }

        return $this->redirectToRoute('app_chambre_index', [], Response::HTTP_SEE_OTHER);
    }
    public function addchambre(Request $req, EntityManagerInterface $em)
    {
        $etage = $req->query->get("etage") ; 
        $prix = $req->query->get("prix") ;
        $capacite =$req->query->get("capacite");
    $Cons = new Chambre() ;
    $Cons->setEtage($etage) ;
    $Cons->setPrix($prix);
    $Cons->setCapacite($capacite);
    try {
        $em = $this->getDoctrine()->getManager();
        $em->persist($Cons);
        $em->flush();
        return new JsonResponse("add success",200);
    }catch (\Exception $ex) {
        return new Response("exception" . $ex->getMessage());
    }
}
#[Route('/mehdi/liste', name: 'liste_chambre')]
public function showMAction(ChambreRepository $repository): JsonResponse
    {
        $cons = $repository->findAll();
        return $this->json(
            $cons,
            200,
            [],
            [ObjectNormalizer::CIRCULAR_REFERENCE_HANDLER => function () {
                return 'symfony5';
            }]
        );
    }
}
