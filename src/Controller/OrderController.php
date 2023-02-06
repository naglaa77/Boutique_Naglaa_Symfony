<?php

namespace App\Controller;

use App\Form\OrderType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OrderController extends AbstractController
{
    #[Route('/commande', name: 'order')]
    public function index(): Response
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
        ]);
    }
}
