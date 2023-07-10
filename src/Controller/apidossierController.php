<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\DossierMedical;
use App\Repository\DossierMedicalRepository;

use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use App\Form\BilanType;
use App\Form\SearchBType;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;

class apidossierController extends AbstractController
{
    #[Route('/morbile', name: 'app_mobrile')]
    public function index(): Response
    {
        return $this->render('mobile/index.html.twig', [
            'controller_name' => 'apidossierController',
        ]);
    }

    #[Route('/allDossier', name: 'listeDossierJSON')]
    public function allDossier(DossierMedicalRepository $repo, SerializerInterface $serializer)
    {
        $reservations = $repo->findAll();
       // var_dump($reservations);
        $json = $serializer->serialize($reservations, 'json', ['groups' => "DossierMedical"]);
       
        return new Response($json);
    
    }
    #[Route ("/addDossierJSON/new", name: "addDossierJSON")]

public function addDossierJSON(Request $req, NormalizerInterface $Normalizer)
{
 
$em = $this->getDoctrine()->getManager();
$reservation = new DossierMedical();
$reservation->setCertificat($req->get('certificat'));
$reservation->getGroupeSanguin($req->get('groupe_sanguin'));



$em->persist($reservation);
$em->flush();

$jsonContent = $Normalizer->normalize($reservation, 'json', ['groups' => "DossierMedical"]);

return new Response(json_encode ($jsonContent)) ;
   
}
#[Route ("/updateDossierJSON/{id}", name: "updateDossierJSON")]

public function updateDossierJSON(Request $req, $id, NormalizerInterface $Normalizer)
{
 
$em = $this->getDoctrine()->getManager();
$reservation = $em->getRepository(DossierMedical::class)->find($id);
$reservation = new DossierMedical();
$reservation->setCertificat($req->get('certificat'));
$reservation->getGroupeSanguin($req->get('groupe_sanguin'));


$em->flush();

$jsonContent = $Normalizer->normalize($reservation, 'json', ['groups' => "DossierMedical"]);

return new Response(json_encode ($jsonContent)) ;
   
}


#[Route ("/deleteDossierJSON/{idRes}", name: "deleteDossierJSON")]

public function deleteDossierJSON(Request $req, $idRes, NormalizerInterface $Normalizer)
{
 
$em = $this->getDoctrine()->getManager();
$reservation = $em->getRepository(DossierMedical::class)->find($idRes);

$em->remove($reservation);

$em->flush();

$jsonContent = $Normalizer->normalize($reservation, 'json', ['groups' => "DossierMedical"]);

return new Response("Dossier deleted successfully" . json_encode ($jsonContent)) ;
   
}
}