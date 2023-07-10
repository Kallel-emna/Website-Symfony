<?php

namespace App\Controller;

use App\Entity\Chambre;
use Symfony\Component\Form\FormError;

use App\Entity\Service;
use App\Form\ServiceType;
use App\Repository\ServiceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Serializer\Serializer;

#[Route('/service')]
class ServiceController extends AbstractController
{
    #[Route('/', name: 'app_service_index', methods: ['GET'])]
    public function index(ServiceRepository $serviceRepository): Response
    {
        return $this->render('service/index.html.twig', [
            'services' => $serviceRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_service_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ServiceRepository $serviceRepository): Response
    {
        $service = new Service();
        $form = $this->createForm(ServiceType::class, $service);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $nom_service = $service->getNomService();
    
            if ($serviceRepository->existsByName($nom_service)) {
                $form->get('nom_service')->addError(new FormError('Le nom du service existe déjà.'));
            } else {
                $serviceRepository->save($service, true);
    
                return $this->redirectToRoute('app_service_index', [], Response::HTTP_SEE_OTHER);
            }
        }
    
        return $this->renderForm('service/new.html.twig', [
            'service' => $service,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_service_show', methods: ['GET'])]
    public function show(Service $service): Response
    {
        $chambre= $service->getChambres();
    
        return $this->render('service/show.html.twig', [
            'service' => $service,
            'chambre' => $chambre,
        ]);
    }
    
    #[Route('/{id}/edit', name: 'app_service_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Service $service, ServiceRepository $serviceRepository): Response
    {
        $form = $this->createForm(ServiceType::class, $service);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            // Vérifier si un autre service a le même nom
            $existingService = $serviceRepository->findOneBy(['nom_service' => $service->getNomService()]);
            if ($existingService && $existingService->getId() !== $service->getId()) {
                $form->get('nom_service')->addError(new FormError('Le nom du service existe déjà.'));
                return $this->renderForm('service/edit.html.twig', [
                    'service' => $service,
                    'form' => $form,
                ]);
            }
    
            $serviceRepository->save($service, true);
    
            return $this->redirectToRoute('app_service_index', [], Response::HTTP_SEE_OTHER);
        }
    
        return $this->renderForm('service/edit.html.twig', [
            'service' => $service,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_service_delete', methods: ['POST'])]
    public function delete(Request $request, Service $service, ServiceRepository $serviceRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$service->getId(), $request->request->get('_token'))) {
            $serviceRepository->remove($service, true);
        }

        return $this->redirectToRoute('app_service_index', [], Response::HTTP_SEE_OTHER);
    }

}
