<?php

namespace App\Classe;
use Symfony\Component\HttpFoundation\RequestStack;

use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;

use App\Repository\ProductRepository;


class Cart 
{

    protected $requestStack;
    protected $entityManger;

    public function __construct(EntityManagerInterface $entityManger,RequestStack $requestStack) 
    {
        $this->requestStack = $requestStack;
        $this->entityManger = $entityManger;   
        
    }

    public function add($id)

    {
        $cart = $this->requestStack->getSession()->get('cart',[]);

        if (!empty($cart[$id])) {
            $cart[$id]++;

        }else {

            $cart[$id] = 1;
        }

        $this->requestStack->getSession()->set('cart',$cart);

    }

    public function get()
    {
        return $this->requestStack->getSession()->get('cart');

    }

    public function remove()
    {
        return $this->requestStack->getSession()->remove('cart');

    }

    public function delete($id)
    {
        $cart = $this->requestStack->getSession()->get('cart',[]);  
        unset($cart[$id]);
        return $this->requestStack->getSession()->set('cart',$cart)  ;      
    }
    public function decrease($id)
    {

        $cart = $this->requestStack->getSession()->get('cart',[]);

        if ($cart[$id] > 1) {
            // retirer une quantite 
            $cart[$id]--;

        }else {
            // supprimer mon produit
            unset($cart[$id]);
        }
        return $this->requestStack->getSession()->set('cart',$cart)  ; 
    }

    public function getFull()
    {

       $cartComplete = [];
        if ($this->get()) {
            foreach ($this->get() as $id => $quantity) {
                $product_object = $this->$entityManger->getRepository(Product::class)->findOneById($id);
                
                if (!$product_object) {

                    $this->delete($id);
                    continue;
                }

                $cartComplete[] = [
                    'product' => $product_object,
                    'quantity' => $quantity
                ];
            
            }

        }
        return $cartComplete;
    }

}
