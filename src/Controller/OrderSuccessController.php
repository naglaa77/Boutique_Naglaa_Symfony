<?php

namespace App\Controller;

use App\Entity\Order;
use App\Classe\Cart;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OrderSuccessController extends AbstractController
{
    #[Route('/commande/merci/{stripeSessionId}', name: 'order_validate')]
    public function index(Cart $cart,$stripeSessionId,EntityManagerInterface $entityManager): Response
    {
        $order = $entityManager->getRepository(Order::class)->findOneByStripeSessionId($stripeSessionId);
        
        if (!$order || $order->getUser() != $this->getUser()) 
        {
            return $this->redirectToRoute('home');

        }

if (!$order->IsIsPaid())
{
        //vider la session cart
        $cart->remove();
    
        // Modifier le statut isPaid de notre commande en mettant 1
        $order->setIsPaid(1);
        $entityManager->flush();
        //Envoyer un email a notre client pour lui confirmer sa commande
}

        //afficher les quelques information de la commande de l'utilisateur
        //dd($order);
        return $this->render('order_success/index.html.twig',[
            'order' => $order
        ]);
    }
}
