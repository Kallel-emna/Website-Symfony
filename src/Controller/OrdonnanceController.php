<?php

namespace App\Controller;

use App\Entity\Ordonnance;
use App\Form\OrdonnanceType;
use App\Repository\OrdonnanceRepository;
use App\Repository\UserRepository;
use App\Entity\Consultation;
use App\Form\SearchType  ;
use App\Form\ConsultationType;
use App\Repository\ConsultationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Dompdf\Dompdf;
use Dompdf\Options;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

#[Route('/ordonnance')]
class OrdonnanceController extends AbstractController
{
    #[Route('/', name: 'app_ordonnance_index', methods: ['GET', 'POST'])]
    public function index(OrdonnanceRepository $ordonnanceRepository,ConsultationRepository $ConsulatationRepository,UserRepository $UserRepository,Request $request): Response
    { 
      
        $formSearch= $this->createForm(SearchType::class); 
       $formSearch->handleRequest($request);
         if($formSearch->isSubmitted()){
           $nompatient= $formSearch->get('nompatient')->getData();
           $result= $ordonnanceRepository->search($nompatient);    
           return $this->renderForm('ordonnance/index.html.twig',
               array('ordonnances'=>$result,
               'consultations' => $ConsulatationRepository->findAll(),
                   'searchForm'=>$formSearch));
       }
        return $this->renderForm('ordonnance/index.html.twig', [
            'ordonnances' => $ordonnanceRepository->findAll(),
            'consultations' => $ConsulatationRepository->findAll(),
            'searchForm'=>$formSearch
        ]);
    }
  
    #[Route('/tri', name: 'app_ordonnance_tri', methods: ['GET', 'POST'])]
    public function index1(OrdonnanceRepository $ordonnanceRepository,ConsultationRepository $ConsulatationRepository,UserRepository $UserRepository,Request $request): Response
    { 
      
        $formSearch= $this->createForm(SearchType::class); 
       $formSearch->handleRequest($request);
       $sortbynom=$ordonnanceRepository->sortbynom();
         if($formSearch->isSubmitted()){
           $nompatient= $formSearch->get('nompatient')->getData();
           $result= $ordonnanceRepository->search($nompatient);    
           return $this->renderForm('ordonnance/index.html.twig',
               array('ordonnances'=>$result,
               'consultations' => $ConsulatationRepository->findAll(),
                   'searchForm'=>$formSearch,
                   "ordonnances"=>$sortbynom

                ));
       }
        return $this->renderForm('ordonnance/index.html.twig', [
            'ordonnances' => $ordonnanceRepository->findAll(),
            'consultations' => $ConsulatationRepository->findAll(),
            'searchForm'=>$formSearch,
            "ordonnances"=>$sortbynom
        ]);
    }
    #[Route('/new', name: 'app_ordonnance_new', methods: ['GET', 'POST'])]
    public function new(Request $request, OrdonnanceRepository $ordonnanceRepository): Response
    {
        $ordonnance = new Ordonnance();
        $form = $this->createForm(OrdonnanceType::class, $ordonnance);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $ordonnanceRepository->save($ordonnance, true);

            return $this->redirectToRoute('app_ordonnance_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('ordonnance/new.html.twig', [
            'ordonnance' => $ordonnance,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_ordonnance_show', methods: ['GET'])]
    public function show(Ordonnance $ordonnance): Response
    {
       
      return $this->render('ordonnance/show.html.twig', [
            'ordonnance' => $ordonnance
        ]);
        
      
    }

    #[Route('/{id}/edit', name: 'app_ordonnance_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Ordonnance $ordonnance, OrdonnanceRepository $ordonnanceRepository): Response
    {
        $form = $this->createForm(OrdonnanceType::class, $ordonnance);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $ordonnanceRepository->save($ordonnance, true);

            return $this->redirectToRoute('app_ordonnance_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('ordonnance/edit.html.twig', [
            'ordonnance' => $ordonnance,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_ordonnance_delete', methods: ['POST'])]
    public function delete(Request $request, Ordonnance $ordonnance, OrdonnanceRepository $ordonnanceRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$ordonnance->getId(), $request->request->get('_token'))) {
            $ordonnanceRepository->remove($ordonnance, true);
        }

        return $this->redirectToRoute('app_ordonnance_index', [], Response::HTTP_SEE_OTHER);
    }
    #[Route('/ordonnance1/{id}', name: 'app_ordonnance_ordonnance', methods: ['GET'])]
    public function ordonnance1(OrdonnanceRepository $ordonnanceRepository,$id)
    { 
          // Configure Dompdf according to your needs
          $pdfOptions = new Options();
          $pdfOptions->set('defaultFont', 'Arial');
          
          // Instantiate Dompdf with our options
          $dompdf = new Dompdf($pdfOptions);
          $ordonnance = $ordonnanceRepository->findOneByid($id);
          // Retrieve the HTML generated in our twig file
          $html = $this->renderView('ordonnance/ordonnance1.html.twig', [
            'ordonnances' => $ordonnance
        ]);
        
          
          // Load HTML to Dompdf
          $dompdf->loadHtml($html);
          
          // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
          $dompdf->setPaper('A4', 'portrait');
  
          // Render the HTML as PDF
          $dompdf->render();
  
          // Output the generated PDF to Browser (inline view)
          $dompdf->stream("mypdf.pdf", [
              "Attachment" => false
          ]);

    }
    
}
