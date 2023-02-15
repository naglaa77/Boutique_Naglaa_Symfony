<?php

namespace App\Controller;

use App\Classe\Cart;
use App\entity\Order;
use App\entity\Carrier;
use App\Form\OrderType;
use App\Entity\OrderDetails;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class OrderController extends AbstractController
{
    #[Route('/commande', name: 'order')]
    public function index(Cart $cart,Request $request): Response
    {
    //dd($this->getUser()->getAddresses()->getValues());

        if (!$this->getUser()->getAddresses()->getValues()) 
        {
           return $this->redirectToRoute('account_address_add');
        }
        $form = $this->createForm(OrderType::class,null,[// utlise null parceque on liee pas avec entity 
            'user' =>$this->getUser(), // pour dire a form recuperer l'info pour utlisateur connectee

        ]);


        return $this->render('order/index.html.twig',[

            'form' => $form->createView(),
            'cart' => $cart->getFull()
        ]);
    }

    #[Route('/commande/recapitulatif', name: 'order_recap', methods: ["POST"] )]
    public function add(Cart $cart,Request $request,EntityManagerInterface $entityManager ): Response
    {
    
        $form = $this->createForm(OrderType::class,null,[// utlise null parceque on liee pas avec entity 
            'user' =>$this->getUser(), // pour dire a form recuperer l'info pour utlisateur connectee

        ]);
        $form->handleRequest($request);
        

        if ($form->isSubmitted() && $form->isValid()) 
        {
          //dd($form->getData());
             $date = new \DateTime();
            $carriers = $form->get('carriers')->getData();
            $delivery = $form->get('addresses')->getData(); // it means address of delivery
            $delivery_content = $delivery->getFirstName().''.$delivery->getLastName();
            $delivery_content =  $delivery->getPhone();
            if ($delivery->getCompany()) 
            {
                $delivery_content .='<br>' .$delivery->getCompany();
            } 
            $delivery_content .='<br>' .$delivery->getAddress();  
            $delivery_content .='<br>' .$delivery->getPostal().''.$delivery->getCity();           
            $delivery_content .='<br>' .$delivery->getCountry();                    
           //dd($delivery_content);
        
            // Enregistrer ma commande Order()
            $order = new Order();
            $reference = $date->format('dmY').'-'.uniqid();
            $order->setReference($reference);
            $order->setUser($this->getUser());
            $order->setCreatedAt($date);
            $order->setCarrierName( $carriers->getName());
            $order->setCarrierPrice( $carriers->getPrice());
            $order->setDelivery( $delivery_content);
            $order->setIsPaid(0);
            $entityManager->persist($order);

        // Enregisterer mes produits OrderDetails()

        $orderDetails = new OrderDetails();
         $orderDetails->setMyOrder($order);
        foreach ($cart->getFull() as $product) {
            $orderDetails = new OrderDetails();
            $orderDetails->setMyOrder($order);
            $orderDetails->setProduct($product['product']->getName());  
            $orderDetails->setQuantity($product['quantity']);
            $orderDetails->setPrice($product['product']->getPrice());
            $orderDetails->setTotal($product['product']->getPrice() * $product['quantity']);
            //dd($product);
            $entityManager->persist($orderDetails);

        }
        //dd($order);
            $entityManager->flush();


            return $this->render('order/add.html.twig',[
                'cart' => $cart->getFull(),
                'carrier' => $carriers,
                'delivery' =>  $delivery_content,
                'reference' =>$order->getReference()
            ]);
        }

        return $this->redirectToRoute('cart');
    }

}
