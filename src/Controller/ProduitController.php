<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Form\ProduitType;
use App\Repository\ProduitRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Notifier\Notification\Notification;
use Symfony\Component\Notifier\NotifierInterface;


#[Route('/produit')]
class ProduitController extends AbstractController
{
    #[Route('/', name: 'app_produit_index', methods: ['GET'])]
    public function index(ProduitRepository $produitRepository): Response
    {
        return $this->render('produit/index.html.twig', [
            'produits' => $produitRepository->findAll(),
        ]);
    }
    
    #[Route('/medecin_show', name: 'app_produit_index2', methods: ['GET'])]
    public function index2(ProduitRepository $produitRepository): Response
    {
        return $this->render('produit/medecin_show.html.twig', [
            'produits' => $produitRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_produit_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ProduitRepository $produitRepository): Response
    {
        $produit = new Produit();
        $form = $this->createForm(ProduitType::class, $produit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid() && !$produit->isLowQuantity()) {
            $produitRepository->save($produit, true);
            $produitRepository->sms();

            return $this->redirectToRoute('app_produit_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('produit/new.html.twig', [
            'produit' => $produit,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_produit_show', methods: ['GET'])]
    public function show(Produit $produit): Response
    {
       
        return $this->render('produit/show.html.twig', [
            'produit' => $produit,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_produit_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Produit $produit, ProduitRepository $produitRepository, NotifierInterface $notifier): Response
    {
        $form = $this->createForm(ProduitType::class, $produit);
        $form->handleRequest($request);

        //$notifier->send(new Notification('Il y a produit qui est inférieur à 3', ['browser']));

        if ($form->isSubmitted() && $form->isValid() && !$produit->isLowQuantity()) {
            $produitRepository->save($produit, true);
            /*if($produit->isLowQuantity()){
                $notifier->send(new Notification('Veuillez voir la quantité du produit, elle doit etre <3!', ['browser']));
            }*/
            
            return $this->redirectToRoute('app_produit_index', [], Response::HTTP_SEE_OTHER);
        }
        else if($produit->isLowQuantity()){
            $notifier->send(new Notification('Veuillez voir la quantité du produit, elle doit etre supérieure à 3!', ['browser']));
        }

        return $this->renderForm('produit/edit.html.twig', [
            'produit' => $produit,
            'form' => $form,
        ]);
    }

    
    #[Route('/{id}/reduce', name: 'app_produit_reduce', methods: ['GET', 'POST'])]
    public function reduce(Request $request, Produit $produit, ProduitRepository $produitRepository): Response
    {
        $quantite = $produit->getQuantite();
        if ($quantite > 0) {
             $produit->setQuantite($quantite - 1);
             $this->getDoctrine()->getManager()->flush();
         }
         
            return $this->redirectToRoute('app_produit_index' , [], Response::HTTP_SEE_OTHER);
    }


    #[Route('/{id}', name: 'app_produit_delete', methods: ['POST'])]
    public function delete(Request $request, Produit $produit, ProduitRepository $produitRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$produit->getId(), $request->request->get('_token'))) {
            $produitRepository->remove($produit, true);
        }
        return $this->redirectToRoute('app_produit_index', [], Response::HTTP_SEE_OTHER);
    }
}
?>