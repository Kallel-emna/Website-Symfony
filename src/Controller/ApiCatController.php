<?php
namespace App\Controller;

use App\Entity\Categorie;
use App\Repository\CategorieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;


use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\SerializerInterface;


class ApiCatController extends AbstractController
{
    #[Route('/allJSON', name: 'listeJSON')]
    public function allreservations(CategorieRepository $repo, SerializerInterface $serializer)
{
    $categorie = $repo->findAll();
   // var_dump($reservations);
    $json = $serializer->serialize($categorie, 'json', ['groups' => "categorie"]);
   
    return new Response($json);

}

#[Route ("/categorieJSON/{id}", name: "categorieJSON")]

public function ResparId($id, NormalizerInterface $normalizer, CategorieRepository $repo)
{
 
$categorie = $repo->find($id);

$categorieNormalises = $normalizer->normalize($categorie, 'json', ['groups' => "categorie"]);

return new Response(json_encode ($categorieNormalises)) ;
   
}

//https://127.0.0.1:8000/addJSON/new?nom_cat=testest
#[Route ("/addJSON/new", name: "addJSON")]

public function addreservationJSON(Request $req, NormalizerInterface $Normalizer)
{
 
$em = $this->getDoctrine()->getManager();
$categorie = new Categorie();
$categorie->setNomCat($req->get('nom_cat'));

$em->persist($categorie);
$em->flush();

$jsonContent = $Normalizer->normalize($categorie, 'json', ['groups' => "categorie"]);

return new Response(json_encode ($jsonContent)) ;
   
}

//https://127.0.0.1:8000/updateJSON/5?nom_cat=test
#[Route ("/updateJSON/{id}", name: "updateJSON")]

public function updateJSON(Request $req, $id, NormalizerInterface $Normalizer)
{
 
$em = $this->getDoctrine()->getManager();
$categorie = $em->getRepository(Categorie::class)->find($id);
$categorie->setNomCat($req->get('nom_cat'));


$em->flush();

$jsonContent = $Normalizer->normalize($categorie, 'json', ['groups' => "categorie"]);

return new Response(json_encode ($jsonContent)) ;
   
}


#[Route ("/deleteJSON/{id}", name: "deleteJSON")]
public function deleteJSON(Request $req, $id, NormalizerInterface $Normalizer)
{
    $em = $this->getDoctrine()->getManager();
    $categorie = $em->getRepository(Categorie::class)->find($id);

    if (!$categorie) {
        throw $this->createNotFoundException(
            'No category found for id '.$id
        );
    }

    $em->remove($categorie);
    $em->flush();

    $jsonContent = $Normalizer->normalize($categorie, 'json', ['groups' => "categorie"]);

    return new JsonResponse("Category deleted successfully", 200);
}

}
