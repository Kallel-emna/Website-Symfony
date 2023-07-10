<?php
namespace App\Services;

use App\Repository\ProduitRepository;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class CartServices {
    private $session;
    private $repoProduct;
    private $tva=0.2;

    public function __construct(SessionInterface $session, ProduitRepository $repoProduct)
    {
        $this->session = $session;
        $this->repoProduct = $repoProduct;
    }

    public function getCart(){
        return $this->session->get('cart',[]);
    }

    public function updateCart($cart){
        $this->session->set('cart',$cart);
        $this->session->set('cartData',$this->getFullCart());       
    }

    public function getFullCart(){
        $cart = $this->getCart();
        $fullCart=[];
        $quantityCart=0;
        $subTotal=0;

        foreach ($cart as $id => $quantity){
            $produit = $this->repoProduct->find($id);
            if($produit){
                $fullCart['produits'][] = [
                    "quantity" => $quantity,
                    "produit" => $produit
                ];
                $quantityCart += $quantity;
                $subTotal += $quantity * $produit->getPrix();
            }else{
                $this->deleteCart($id);
            }
        }

        $fullCart['data'] = [
            "quantityCart" => $quantityCart,
            "subTotalHT" => $subTotal,
            "taxe" => round($subTotal*$this->tva,2),
            "subTotalTTC" => round(($subTotal + ($subTotal*$this->tva)),2)
        ];
        return $fullCart;
    }

    public function addToCart($id){
        $cart = $this->getCart();
        if(isset($cart[$id])){
            $cart[$id]++;
        }else{
            $cart[$id] = 1;
        }
        $this->updateCart($cart);
    }

    public function deleteFromCart($id){
        $cart = $this->getCart();
        if(isset($cart[$id])){
            if($cart[$id]>1){
                $cart[$id]--;
            }else{
                unset($cart[$id]);
            }
            $this->updateCart($cart);
        }
    }

    public function deleteAllToCart($id){
        $cart = $this->getCart();
        if(isset($cart[$id])){
            unset($cart[$id]);
            $this->updateCart($cart);
        }
    }

    public function deleteCart(){
        $this->updateCart([]);
    }

    public function resultat($id){
        $cart = $this ->getCart();
        $produit = $this->repoProduct->find($id);

        if(isset($cart[$id])){
            if($cart[$id]> $produit->getQuantite()){
                $cart[$id]--;
            }else{
                unset($cart[$id]);
            }
            $this->updateCart($cart);
        }
    }
}

?>