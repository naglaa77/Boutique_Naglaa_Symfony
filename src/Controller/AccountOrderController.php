<?php

namespace App\Controller;

use App\Entity\Order;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccountOrderController extends AbstractController
{
    #[Route('/compte/mes_commandes', name: 'account_order')]
    public function index(EntityManagerInterface $entityManager): Response
    {

        $orders = $entityManager->getRepository(Order::class)->findSuccessOrders($this->getUser());//cette fonction elle ne exist pas je vais creer dans order repository
        //dd($orders);
        return $this->render('account/order.html.twig',[
            'orders' => $orders
        ]);
    }

    #[Route('/compte/mes_commandes/{reference}', name: 'account_order_show')]
        public function show($reference,EntityManagerInterface $entityManager): Response
        {

            $order = $entityManager->getRepository(Order::class)->findOneByReference($reference);
            
            if (!$order || $order->getUser() != $this->getUser())
            {
                return $this->redirectToRoute('account_order');
            }
            return $this->render('account/order_show.html.twig',[
                'order' => $order
            ]);
        }
}
