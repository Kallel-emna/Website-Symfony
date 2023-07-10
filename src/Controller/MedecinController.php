<?php

namespace App\Controller;

use App\Entity\BilanMedical;
use App\Repository\BilanMedicalRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
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

class MedecinController extends AbstractController
{
    #[Route('/medecin', name: 'app_medecin')]
    public function index(): Response
    {
        return $this->render('medecin/backMedecin.html.twig', [
            'controller_name' => 'MedecinController',
        ]);
    }

    #[Route('/bilan', name: 'app_bilan')]
    public function listbilan(BilanMedicalRepository $repository, Request $request)
    {
        $bilan = $repository->findAll();
        $formSearch = $this->createForm(SearchBType::class);
        $formSearch->handleRequest($request);
        if ($formSearch->isSubmitted()) {
            $id = $formSearch->get('id')->getData();
            $result = $repository->search($id);     // recherche
            return $this->renderForm(
                'medecin/affichageBilan.html.twig',
                array(
                    'bilan' => $result,

                    "searchForm" => $formSearch
                )
            );
        }



        return $this->renderForm('medecin/affichageBilan.html.twig', array('bilan' => $bilan, 'searchForm' => $formSearch));
    }
    #[Route('/Students', name: 'list_student')]
    public function liststudent(BilanMedicalRepository $repository, Request $request)
    {


        $sortBymoyenne = $repository->sortBymoyenne(); //afficher avec tri
        $formSearch = $this->createForm(SearchBType::class);
        $formSearch->handleRequest($request);
        if ($formSearch->isSubmitted()) {
            $id = $formSearch->get('id')->getData();
            $result = $repository->search($id);     // recherche
            return $this->renderForm(
                'medecin/affichageBilan.html.twig',
                array(
                    'bilan' => $result,

                    "searchForm" => $formSearch,   "sortBymoyenne" => $sortBymoyenne
                )
            );
        }

        return $this->renderForm('medecin/affichageBilanSort.html.twig', array('searchForm' => $formSearch, "sortBymoyenne" => $sortBymoyenne));
    }


    #[Route('/AddB', name: 'addB')]
    public function addForm(ManagerRegistry $doctrine, Request $request)
    {
        $bilan = new BilanMedical;
        $form = $this->createForm(BilanType::class, $bilan);
        $form->handleRequest($request);   // Pour traiter les données du formulaire 

        if ($form->isSubmitted() && $form->isValid()) {

            // tethat par defaut  $classroom->setName("rania") ;
            $em = $doctrine->getManager(); // pour faire ajout dans la base de donnée 
            $em->persist($bilan); // t7adher requete teina
            $em->flush(); // mise a jour o tzidha 
            // $repository->add($classroom,True) ; 
            return  $this->redirectToRoute("app_bilan");
        }
        return $this->renderForm("medecin/add.html.twig", array("form" => $form));
    }
    #[Route('/removeB/{id}', name: 'removeB')]

    public function removeBilan(ManagerRegistry $doctrine, $id, BilanMedicalRepository $repository)
    {
        $bilan = $repository->find($id);
        $em = $doctrine->getManager();
        $em->remove($bilan);
        $em->flush();
        return  $this->redirectToRoute("app_bilan");
    }
    #[Route('/updateB/{id}', name: 'updateB')]
    public function  updateBilan($id, BilanMedicalRepository $repository, ManagerRegistry $doctrine, Request $request)
    {
        $bilan = $repository->find($id); //recuperer l'objet par id 
        $form = $this->createForm(BilanType::class, $bilan);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {


            $em = $doctrine->getManager();
            $em->flush();
            return  $this->redirectToRoute("app_bilan");
        }
        return $this->renderForm("medecin/update.html.twig", array("form" => $form));
    }
    #[Route('/showBilanPatient/{id}', name: 'showBilan')]
    public function show(BilanMedicalRepository $repository, $id, DossierMedicalRepository $repositoryR)
    {
        $bilan = $repository->getPatientt($id);
        return $this->render("medecin/show.html.twig", array(

            'bilan' => $bilan, 'classe' => $id
        ));
    }
    #[Route('/showDossier/{id}', name: 'show')]
    public function showD(BilanMedicalRepository $repository, $id, DossierMedicalRepository $repositoryR)

    {
        $client = $repositoryR->find($id);
        $bilan = $repository->getPatienttt($id);
        return $this->render("medecin/showw.html.twig", array(

            'bilan' => $bilan, 'classe' => $id
        ));
    }

    #[Route('/list', name: 'test')]


    public function showMAction(BilanMedicalRepository $repository): JsonResponse
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



    #[Route('/ajout', name: 'advdd')]

    public function addconsul(Request $req, EntityManagerInterface $em)
    {


        $bilan = new BilanMedical();
        $Antecedents = $req->query->get("Antecedents");
        $Taille = $req->query->get("Taille");
        $Poids = $req->query->get("Poids");
        $ExamensBiologiques = $req->query->get("ExamensBiologiques");
        $ImagerieMedicale = $req->query->get("ImagerieMedicale");
        $em = $this->getDoctrine()->getManager();



        $bilan->setAntecedents($Antecedents);
        $bilan->setTaille($Taille);
        $bilan->setPoids($Poids);
        $bilan->setExamensBiologiques($ExamensBiologiques);
        //$bilan->setImagerieMedicale($ImagerieMedicale);


        $em->persist($bilan);
        $em->flush();
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($bilan);
        return new JsonResponse($formatted);
    }
    #[Route('/delete', name: 'addd')]

    public function deleteSeanceAction(Request $request)
    {
        $id = $request->get("id");

        $em = $this->getDoctrine()->getManager();
        $bilan = $em->getRepository(BilanMedical::class)->find($id);
        if ($bilan != null) {
            $em->remove($bilan);
            $em->flush();

            $serialize = new Serializer([new ObjectNormalizer()]);
            $formatted = $serialize->normalize("bilan a ete supprimee avec success.");
            return new JsonResponse($formatted);
        }
        return new JsonResponse("id Seance invalide.");
    }
    #[Route('/update', name: 'add')]

    public function modifierSeanceAction(BilanMedicalRepository $bilanMedicalRepository, Request $request)
    {

        $em = $this->getDoctrine()->getManager();

        $bilan = $bilanMedicalRepository->find($request->get('id'));

        $bilan->setAntecedents($request->get('Antecedents'));
        $bilan->setTaille($request->get('Taille'));
        $bilan->setPoids($request->get('Poids'));
        $bilan->setExamensBiologiques($request->get('ExamensBiologiques'));
        //$bilan->setImagerieMedicale($request->get('ImagerieMedicale'));


        $em->persist($bilan);
        $em->flush();
        $serialize = new Serializer([new ObjectNormalizer()]);
        $formatted = $serialize->normalize("bilan a ete modifie avec success.");
        return new JsonResponse($formatted);
    }
}
