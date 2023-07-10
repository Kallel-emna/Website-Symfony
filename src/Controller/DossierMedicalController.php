<?php

namespace App\Controller;

use App\Entity\BilanMedical;
use App\Entity\DossierMedical;
use App\Repository\BilanMedicalRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\DossierType;
use App\Repository\DossierMedicalRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Persistence\ManagerRegistry;
use App\Form\SearchDType;

class DossierMedicalController extends AbstractController
{
    #[Route('/dossier/medical', name: 'app_dossier_medical')]
    public function index(): Response
    {
        return $this->render('dossier_medical/index.html.twig', [
            'controller_name' => 'DossierMedicalController',
        ]);
    }
 
    #[Route('/dossier', name: 'app_dossier')]
    public function listdossier (DossierMedicalRepository  $repository , Request $request)
    {
        $dossier=$repository->findAll(); 
        $formSearch= $this->createForm(SearchDType::class); 
        $formSearch->handleRequest($request);
          if($formSearch->isSubmitted()){
            $id= $formSearch->get('id')->getData();
            $result= $repository->search($id);     // recherche
            return $this->renderForm('dossier_medical/affichageDossier.html.twig',
                array('dossier'=>$result,
                  
                    "searchForm"=>$formSearch));
        }
         
        return $this->renderForm('dossier_medical/affichageDossier.html.twig',array('dossier'=>$dossier, "searchForm"=>$formSearch)) ; 
    }


    #[Route('/AddDossier', name: 'add_dossier')]
    public function addForm(ManagerRegistry $doctrine,Request $request)
    {
        $dossier= new DossierMedical;
        $form= $this->createForm(DossierType::class,$dossier);
        $form->handleRequest($request) ;   // Pour traiter les données du formulaire 
      
        if ($form->isSubmitted() && $form->isValid())
        {
            
          // tethat par defaut  $classroom->setName("rania") ;
             $em= $doctrine->getManager(); // pour faire ajout dans la base de donnée 
             $em->persist($dossier); // t7adher requete teina
             $em->flush(); // mise a jour o tzidha 
           // $repository->add($classroom,True) ; 
             return  $this->redirectToRoute("app_dossier");
         }
        return $this->renderForm("dossier_medical/addDossier.html.twig",array("form"=>$form));
    }
    #[Route('/removeD/{id}', name:'remove1')]

    public function removeDossier(ManagerRegistry $doctrine,$id,DossierMedicalRepository $repository)
    {
        $dossier= $repository->find($id);
        $em = $doctrine->getManager();
        $em->remove($dossier);
        $em->flush();
        return  $this->redirectToRoute("app_dossier");
}
#[Route('/updateD/{id}', name: 'update1')]
public function  updatDossier($id,DossierMedicalRepository $repository,ManagerRegistry $doctrine,Request $request)
{
    $dossier= $repository->find($id); //recuperer l'objet par id 
    $form= $this->createForm(DossierType::class,$dossier);
    $form->handleRequest($request) ;
    if ($form->isSubmitted()){
        $em= $doctrine->getManager();
        $em->flush();
        return  $this->redirectToRoute("app_dossier");
    }
    return $this->renderForm("dossier_medical/updateDossier.html.twig",array("form"=>$form));
}
   

    
  


}
