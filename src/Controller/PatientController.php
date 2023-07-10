<?php

namespace App\Controller;
use App\Entity\Chambre;
use Dompdf\Dompdf;
use App\Entity\Patient;
use App\Form\PatientType;
use App\Repository\ChambreRepository;
use App\Repository\PatientRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


#[Route('/patient')]
class PatientController extends AbstractController
{
    #[Route('/', name: 'app_patient_index', methods: ['GET'])]
    public function index(Request $request, PatientRepository $patientRepository): Response
    {
        
            return $this->render('patient/index.html.twig', [
                'patients' => $patientRepository->findAll(),
            ]);
    }

    #[Route('/new', name: 'app_patient_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $entityManager = $this->getDoctrine()->getManager();

    $patient = new Patient();
    $form = $this->createForm(PatientType::class, $patient);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $chambreId = $form->get('chambre')->getData();
        $chambre = $entityManager->getRepository(Chambre::class)->find($chambreId);

        if ($chambre->getCapacite() >= 1) {
            $patient->setChambre($chambre);
            $entityManager->persist($patient);

            // Vérifier si la date de sortie est remplie
            if (!$patient->getDateSortie()) {
                $chambre->setCapacite($chambre->getCapacite() - 1);
            }

            $entityManager->flush();

            return $this->redirectToRoute('app_patient_index');
        } else {
            $this->addFlash('error', 'La capacité de la chambre est insuffisante.');
        }
    }

    return $this->render('patient/new.html.twig', [
        'form' => $form->createView(),
    ]);
    }
    

    #[Route('/{id}', name: 'app_patient_show', methods: ['GET'])]
    public function generatePdf(Patient $patient, int $id): Response
    {
        $patient = $this->getDoctrine()
            ->getRepository(Patient::class)
            ->find($id);
    
        if (!$patient) {
            throw $this->createNotFoundException('Le patient avec l\'id ' . $id . ' n\'existe pas.');
        }
    
        $dateAdmission = $patient->getDateAdmission();
        $dateSortie = $patient->getDateSortie();
        $prixchambre = $patient->getChambre()->getPrix();
        $nombreJours = null;
    
        if ($dateSortie !== null) {
            $nombreJours = $dateAdmission->diff($dateSortie)->days;
        }
    
        $prixtotal = $nombreJours * $prixchambre;
    
        $html = $this->renderView('patient/show.html.twig', [
            'patient' => $patient,
            'nombreJours' => $nombreJours,
            'prix' => $prixchambre,
            'prixtotal'=> $prixtotal
        ]);
    
        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
    
        // (Optional) Setup the paper size and orientation
        $dompdf->setPaper('A4', 'portrait');
    
        // Render the HTML as PDF
        $dompdf->render();
    
        // Output the generated PDF to the browser
        $output = $dompdf->output();
        $response = new Response($output);
        $response->headers->set('Content-Type', 'application/pdf');
    
        return $response;
    }

    #[Route('/{id}/edit', name: 'app_patient_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Patient $patient, PatientRepository $patientRepository): Response
    {
        $form = $this->createForm(PatientType::class, $patient);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
    
            // Vérifier si le champ date_sortie a été renseigné
            if ($patient->getDateSortie()) {
                $chambre = $patient->getChambre();
                $chambre->setCapacite($chambre->getCapacite() + 1);
                $entityManager->persist($chambre);
            }
    
            $entityManager->flush();
    
            return $this->redirectToRoute('app_patient_index', [], Response::HTTP_SEE_OTHER);
        }
    
        return $this->renderForm('patient/edit.html.twig', [
            'patient' => $patient,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_patient_delete', methods: ['POST'])]
    public function delete(Request $request, Patient $patient, PatientRepository $patientRepository): Response
    {         
            if ($this->isCsrfTokenValid('delete'.$patient->getId(), $request->request->get('_token'))) { 
                $patientRepository->remove($patient, true);
            }
    
            return $this->redirectToRoute('app_service_index', [], Response::HTTP_SEE_OTHER);
    
    
    }
}
