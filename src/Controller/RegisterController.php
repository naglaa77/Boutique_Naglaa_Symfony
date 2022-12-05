<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegisterType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


class RegisterController extends AbstractController
{

    private $entityManager;
    // i create construct fro entitymanganger because i will use it in several places
    public function __construct(EntityManagerInterface $entityManager )
    {
        $this->entityManager = $entityManager;
    }


    #[Route('/inscription', name: 'register')]
    public function index(Request $request,UserPasswordHasherInterface $encoder): Response
    {

        $user = new User();
        $form = $this->createForm(RegisterType::class, $user);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
        
            // get  the user information 
            $user= $form->getData();
            $password =$encoder->hashPassword($user,$user->getPassword()) ;
            $user->setPassword($password);
            //  data freeze for saveing 
            $this->entityManager->persist($user);
            // excute the persit and register dat in data base
           $this->entityManager->flush();
            
        
        }

        return $this->render('register/index.html.twig',[
            'form' => $form->createView()
        ]);
    }
}

