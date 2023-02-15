<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Order;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OrderCancelController extends AbstractController
{
    #[Route('/commande/erreur/{stripeSessionId}', name: 'order_cancel')]
    public function index($stripeSessionId,EntityManagerInterface $entityManager): Response
    {

        $order = $entityManager->getRepository(Order::class)->findOneByStripeSessionId($stripeSessionId);
        
        if (!$order || $order->getUser() != $this->getUser()) 
        {
            return $this->redirectToRoute('home');

        }
        // Envoyer un email a utlisateur pour lui indiquer l'echec de paiement


        return $this->render('order_cancel/index.html.twig',[

            'order' => $order
        ]);
    }
}
