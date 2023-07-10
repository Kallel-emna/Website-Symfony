<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\BilanMedical;
use App\Repository\BilanMedicalRepository;

use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use App\Form\BilanType;
use App\Form\SearchBType;
use App\Repository\DossierMedicalRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;

class mobileraniaController extends AbstractController
{
    #[Route('/mobile', name: 'app_mobile')]
    public function index(): Response
    {
        return $this->render('mobile/index.html.twig', [
            'controller_name' => 'mobileraniaController',
        ]);
    }

    #[Route('/allBilan', name: 'listeBilanJSON')]
    public function allBilan(BilanMedicalRepository $repo, SerializerInterface $serializer)
    {
        $reservations = $repo->findAll();
       // var_dump($reservations);
        $json = $serializer->serialize($reservations, 'json', ['groups' => "BilanMedical"]);
       
        return new Response($json);
    
    }
    #[Route ("/addBilanJSON/new", name: "addBilanJSON")]

public function addBilanJSON(Request $req, NormalizerInterface $Normalizer)
{
 
$em = $this->getDoctrine()->getManager();
$reservation = new BilanMedical();
$reservation->setAntecedents($req->get('antecedents'));
$reservation->setTaille($req->get('taille'));
$reservation->setPoids($req->get('poids'));
$reservation->setExamensBiologiques($req->get('examens_biologiques'));



$em->persist($reservation);
$em->flush();

$jsonContent = $Normalizer->normalize($reservation, 'json', ['groups' => "BilanMedical"]);

return new Response(json_encode ($jsonContent)) ;
   
}
#[Route ("/updateBilanJSON/{id}", name: "updateBilanJSON")]

public function updateBilanJSON(Request $req, $id, NormalizerInterface $Normalizer)
{
 
$em = $this->getDoctrine()->getManager();
$reservation = $em->getRepository(BilanMedical::class)->find($id);
//$reservation->setPrenomInvite($req->get('prenom_invite'));
//$reservation->setNomInvite($req->get('nom_invite'));
$reservation->setAntecedents($req->get('antecedents'));
$reservation->setTaille($req->get('taille'));
$reservation->setPoids($req->get('poids'));
$reservation->setExamensBiologiques($req->get('examens_biologiques'));


$em->flush();

$jsonContent = $Normalizer->normalize($reservation, 'json', ['groups' => "BilanMedical"]);

return new Response(json_encode ($jsonContent)) ;
   
}


#[Route ("/deleteBilanJSON/{idRes}", name: "deleteBilanJSON")]

public function deleteBilanJSON(Request $req, $idRes, NormalizerInterface $Normalizer)
{
 
$em = $this->getDoctrine()->getManager();
$reservation = $em->getRepository(BilanMedical::class)->find($idRes);

$em->remove($reservation);

$em->flush();

$jsonContent = $Normalizer->normalize($reservation, 'json', ['groups' => "BilanMedical"]);

return new Response("Bilan deleted successfully" . json_encode ($jsonContent)) ;
   
}


#[Route('/zebi', name: 'app_service_Sho')]

public function showMAction(BilanMedicalRepository $repository): JsonResponse
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
}