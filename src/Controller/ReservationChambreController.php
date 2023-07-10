<?php

namespace App\Controller;
use Dompdf\Dompdf;
use Symfony\Component\Validator\Constraints\DateTime;
use App\Entity\ReservationChambre;
use App\Form\ReservationChambreType;
use App\Repository\ReservationChambreRepository;
use App\Repository\UserRepository;
use phpDocumentor\Reflection\Types\Null_;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/reservation/chambre')]
class ReservationChambreController extends AbstractController
{
    #[Route('/', name: 'app_reservation_chambre_index', methods: ['GET', 'POST'])]
    public function index(Request $request, UserRepository $userRepository): Response
    {
        $reservationChambreRepository = $this->getDoctrine()->getRepository(ReservationChambre::class);
        $chambreCapacityDecremented = [];
    
        $reservation_chambres = $reservationChambreRepository->findAll();
    
        // Loop through all reservation chambres
        foreach ($reservation_chambres as $reservationChambre) {
            // Get the chambre for the reservation chambre
            $chambre = $reservationChambre->getChambre();
    
            // Check if the chambre has already had its capacity decremented
            if (!isset($chambreCapacityDecremented[$chambre->getId()])) {
                // Get the current system date and format it as a string
                $systemDate = new \DateTime();
                $systemDateString = $systemDate->format('Y-m-d');
    
                // Get the date admission for the reservation chambre and format it as a string
                $dateAdmission = $reservationChambre->getDateAdmission();
                $dateAdmissionString = $dateAdmission->format('Y-m-d');
    
                // If the date admission is equal to the system date, decrement the capacity of the chambre
                if ($dateAdmissionString === $systemDateString) {
                    $chambre->setCapacite($chambre->getCapacite() - 1);
                    $chambreCapacityDecremented[$chambre->getId()] = true;
                }
            }
        }
    
        return $this->render('reservation/index.html.twig', [
            'reservation_chambres' => $reservation_chambres
        ]);
    }
    

    #[Route('/new', name: 'app_reservation_chambre_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ReservationChambreRepository $reservationChambreRepository, UserRepository $userRepository): Response
    {
        $reservationChambre = new ReservationChambre();
        $form = $this->createForm(ReservationChambreType::class, $reservationChambre);
        
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            // Get the selected chambre
            $chambre = $reservationChambre->getChambre();

            if ($chambre == null) {
                // Display an error message
                $this->addFlash('error', 'La chambre n\'existe pas.');
                return $this->redirectToRoute('app_reservation_chambre_new');
            }
            
            if ($chambre->getCapacite() <= 0) {
                // Display an error message
                $this->addFlash('error', 'La chambre est complète.');
                return $this->redirectToRoute('app_reservation_chambre_new');
            }
            
    
            // Check if the date de sortie is defined or not
            if ($reservationChambre->getDateSortie() == NULL) {
                // Check if the date admission is greater than the current date
                if ($reservationChambre->getDateAdmission() > new DateTime()) {
                    // Display an error message
                    $this->addFlash('error', 'La date admission doit être antérieure ou égale à la date actuelle.');
                    return $this->redirectToRoute('app_reservation_chambre_new');
                }
    
                // Decrement the capacity of the chambre
                $chambre->setCapacite($chambre->getCapacite() - 1);
        
                // Set the chambre id to the selected chambre id
                $reservationChambre->setChambre($chambre);
            }
        
            // Save the reservation and update the chambre
            $reservationChambreRepository->save($reservationChambre, true);
        
            return $this->redirectToRoute('app_reservation_chambre_index', [], Response::HTTP_SEE_OTHER);
        }
    
        return $this->renderForm('reservation/new.html.twig', [
            'reservation_chambre' => $reservationChambre,
            'form' => $form,
        ]);
    }
    
    

    


    #[Route('/{id}', name: 'app_reservation_chambre_show', methods: ['GET'])]
    public function show(ReservationChambre $reservationChambre , int $id): Response
    {     $reservationChambre = $this->getDoctrine()
        ->getRepository(ReservationChambre::class)
        ->find($id);

    if (!$reservationChambre) {
        throw $this->createNotFoundException('Le patient avec l\'id ' . $id . ' n\'existe pas.');
    }
    $chambre = $reservationChambre->getChambre();
$avance=0;
    if($chambre->getService()->getNomService()=='radio')
    {
        $avance=$chambre->getPrix()*1;
    } 
    if($chambre->getService()->getNomService()=='chirurgie')
    {
        $avance=$chambre->getPrix()*5;
    }
    if($chambre->getService()->getNomService()=='echographie')
    {
        $avance=$chambre->getPrix()*1;
    }
    if($chambre->getService()->getNomService()=='gynécologie')
    {
        $avance=$chambre->getPrix()*20;
    }
    
    $dateAdmission = $reservationChambre->getDateAdmission();
    $dateSortie = $reservationChambre->getDateSortie();
    $prixchambre = $reservationChambre->getChambre()->getPrix();
    $nombreJours = null;
 
    if ($dateSortie !== null) {
        $nombreJours = $dateAdmission->diff($dateSortie)->days;
    }

    $paye = $nombreJours * $prixchambre;
$prixtotal=$paye-$avance;
    $html = $this->renderView('reservation/show.html.twig', [
        'reservation_chambre' => $reservationChambre,
        'avance'=> $avance,
        'nombreJours' => $nombreJours,
        'prix' => $prixchambre,
        'paye'=>$paye,
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

    #[Route('/{id}/edit', name: 'app_reservation_chambre_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, ReservationChambre $reservationChambre, ReservationChambreRepository $reservationChambreRepository): Response
    {
        $form = $this->createForm(ReservationChambreType::class, $reservationChambre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if($reservationChambre->getDateSortie() != null)
            {
                $chambre = $reservationChambre->getChambre();
                $chambre->setCapacite($chambre->getCapacite() + 1);

            }
        
            $reservationChambreRepository->save($reservationChambre, true);    
        
            return $this->redirectToRoute('app_reservation_chambre_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('reservation/edit.html.twig', [
            'reservation_chambre' => $reservationChambre,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_reservation_chambre_delete', methods: ['POST'])]
    public function delete(Request $request, ReservationChambre $reservationChambre, ReservationChambreRepository $reservationChambreRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$reservationChambre->getId(), $request->request->get('_token'))) {
            $reservationChambreRepository->remove($reservationChambre, true);
        }

        return $this->redirectToRoute('app_reservation_chambre_index', [], Response::HTTP_SEE_OTHER);
    }

    
}
