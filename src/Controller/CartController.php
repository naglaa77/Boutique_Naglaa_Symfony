<?php

namespace App\Controller;
use App\Classe\Cart;
use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\RequestStack;
class CartController extends AbstractController
{

    private $entityManger;

    public function __construct(EntityManagerInterface $entityManger)
    {
        $this->entityManger = $entityManger;

    }

    #[Route('/mon-panier', name: 'cart')]
    public function index(Cart $cart,RequestStack $requestStack,ProductRepository $productRepository): Response
    {
  $session = $requestStack->getSession(); //symfony6
        
        $detaileCart =[];//pour cheque produit un tanleau associative[12=>['product'=> contain de product,'quantity' =>contain de quantite ]]

        foreach($session->get('cart',[]) as $id => $quantity) { // cart =[2 =>3,4=>5]

            $detaileCart[] =[
                'product' => $productRepository->find($id), // [tout l'info de product]
                'quantity' => $quantity
            ];
        }
        return $this->render('cart/index.html.twig',[
        
            'cart' =>$detaileCart
        
        ]);
    }

    #[Route('/cart/add/{id}', name: 'add_to_cart',requirements:['id' => '\d+'])]
    public function add(Cart $cart,$id,ProductRepository $productRepository,RequestStack $requestStack ): Response
    {
        //-	Securisation : est ce que le produit exist
        $session = $requestStack->getSession(); //symfony6
        $product = $productRepository->find($id);

        if (!$product) {
            throw $this->createNotFoundException("le produit $id n'exist pas");
            
        }
    
    //-	Retrouver le panier dans la session sous form de tableau
    //-	Si il n’exist pas encore alors prendre un tableau vide 
        $cart = $session->get('cart',[]);

    //-	Voir le produit ($id) exist deje dans le tableau 
        if(array_key_exists($id,$cart)) {
            $cart[$id]++;

        }else { //-	Sinon ajouter le produit avec quantity 1
            $cart[$id] = 1;

        }
        $session->set('cart',$cart);
        //$request->getSession()->remove('cart');
    
        $flashBag = $session->getBag('flashes');
        //$this->addflash('success',"le produit a bien ete ajoute au panier");
        $flashBag->add('success',"le produit a bien ete ajoute au panier");

       //$cart->add($id);
        
        return $this->redirectToRoute('cart',[
            'slug' => $product->getSlug()

        ]);
    }



    #[Route('/cart/remove', name: 'remove_my_cart')]
    public function remove(Cart $cart): Response
    {

       $cart->remove();
        
        return $this->redirectToRoute('products');
    }

    // delete one product from panier
    #[Route('/cart/delete/{$id}', name: 'delete_to_cart')]
    public function delete(Cart $cart,$id): Response
    {

       $cart->delete($id);
        
        return $this->redirectToRoute('cart');
    }

    #[Route('/cart/decrease/{$id}', name: 'decrease_to_cart')]
    public function decrease(Cart $cart,$id): Response
    {

       $cart->decrease($id);
        
        return $this->redirectToRoute('cart');
    }
}
