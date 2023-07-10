<?php

namespace App\Controller;

use App\Services\CartServices;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends AbstractController
{
    private $cartServices;
    public function __construct(CartServices $cartServices)
    {
        $this->cartServices = $cartServices;
    }

    #[Route('/cart', name: 'app_cart')]
    public function index(): Response
    {
        $cart = $this->cartServices->getFullCart();
        if(!isset($cart['produits'])){
            return $this->redirectToRoute("app_produit_index");
        }
        return $this->render('cart/index.html.twig', [
            'controller_name' => 'CartController',
            'cart' => $cart,
        ]);
    }

    #[Route('/cart/add/{id}', name: 'CartAdd')]
    public function addToCart($id): Response 
    {
        $this->cartServices->addToCart($id);
        return $this->redirectToRoute("app_cart");
    }

    #[Route('/cart/delete/{id}', name: 'CartDelete1')]
    public function deleteFromCart($id): Response
    {
        $this->cartServices->deleteFromCart($id);
        return $this->redirectToRoute("app_cart");
    }

    #[Route('/cart/deleteAll/{id}', name: 'CartDeleteAll')]
    public function deleteAllToCart($id): Response
    {
        $this->cartServices->deleteAllToCart($id);
        return $this->redirectToRoute("app_cart");
    }

    #[Route('/cart/deleteAll', name: 'CartDelete')]
    public function deleteAll(): Response
    {
        $this->cartServices->deleteCart();
        return $this->redirectToRoute("app_produit_index");
    }

}