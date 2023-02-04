<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Address;
use App\Form\AddressType;
use Doctrine\ORM\EntityManagerInterface;

class AccountAddressController extends AbstractController
{

    // private $entityManager;

    // public function  __constructor(EntityManagerInterface $entityManager) {
    
    //     $this->entityManager = $entityManager;
    // }

    #[Route('/compte/address', name: 'account_address')]
    public function index(): Response
    {

        return $this->render('account/address.html.twig');
    }

    #[Route('/compte/ajouter-une-addresse', name: 'account_address_add')]
    public function add(Request $request,EntityManagerInterface $entityManager): Response
    {
       
        $address = new Address();
        
        $form = $this->createForm(AddressType::class, $address);
        $form->handleRequest($request); //dite formelaire ecoute bien a requete
       
        if ($form->isSubmitted() && $form->isValid()) { 

            $address->setUser( $this->getUser());
            $entityManager->persist($address);
            $entityManager->flush();
            return $this->redirectToRoute('account_address');
        }
        
       
        return $this->render('account/addresse_form.html.twig',[
            'form' => $form->createView(),

        ]);
    }

    // modifier l'addresse
    #[Route('/compte/modifier-une-addresse/{id}', name: 'account_address_edit')]
    public function edit(Request $request,EntityManagerInterface $entityManager,$id): Response
    {
       
        $address = $entityManager->getRepository(Address::class)->findOneById($id);
        
        if (!$address  || $address->getUser() !== $this->getUser()  ) {  // verfier si l'address object qui recupere par url est different de user connecter
      
           return $this->redirectToRoute('account_address');
        }

        $form = $this->createForm(AddressType::class, $address);
        $form->handleRequest($request); //dite formelaire ecoute bien a requete
        
        if ($form->isSubmitted() && $form->isValid()) { 
            
            $entityManager->flush();
            return $this->redirectToRoute('account_address');
        }
        
       
        return $this->render('account/addresse_form.html.twig',[
            'form' => $form->createView(),

        ]);
    }

// supprimer l'addresse
    #[Route('/compte/supprimer-une-addresse/{id}', name: 'account_address_delete')]
    public function delete(EntityManagerInterface $entityManager,$id): Response
    {
       
        $address = $entityManager->getRepository(Address::class)->findOneById($id);
        
        if ($address  || $address->getUser() === $this->getUser()  ) {  // verfier si l'address object qui recupere par url est different de user connecter
            $entityManager->remove($address);
            $entityManager->flush();
           
        }
            return $this->redirectToRoute('account_address');

       
    }


}
