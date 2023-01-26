<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Address;
use App\Form\AddressType;

class AccountAddressController extends AbstractController
{
    #[Route('/compte/address', name: 'account_address')]
    public function index(): Response
    {

        return $this->render('account/address.html.twig');
    }

    #[Route('/compte/ajouter-une-addresse', name: 'account_address_add')]
    public function add(): Response
    {
        $address = new Address();
        $form = $this->createForm(AddressType::class, $address);
        return $this->render('account/addresse_add.html.twig',[
            'form' => $form->createView(),

        ]);
    }
}
