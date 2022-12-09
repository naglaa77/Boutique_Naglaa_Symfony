<?php

namespace App\Controller;

use App\Form\ChangePasswordType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AccountPasswordController extends AbstractController
{
    private $entityManager;
    // i create construct fro entitymanganger because i will use it in several places
    public function __construct(EntityManagerInterface $entityManager )
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/compte/modifier-mon-mot-de-passe', name: 'account_password')]
    public function index(Request $request,UserPasswordHasherInterface $encoder): Response
    {

        $notification = null;
        $user = $this->getUser();
        
        $form = $this->createForm(ChangePasswordType::class, $user);

        $form->handleRequest($request);

         if ($form->isSubmitted() && $form->isValid()) {
            $old_pwd = $form->get('old_password')->getData();

            if($encoder->isPasswordValid($user, $old_pwd)) {

                $new_pwd = $form->get('new_password')->getData();
                $password =$encoder->hashPassword($user,$new_pwd) ;
                $user->setPassword($password);
                //  data freeze for saveing 
                // excute the persit and register dat in data base
                 $this->entityManager->flush();
                $notification = "Votre mot de pass a été mis à jour";
            }else {

                $notification = "Votre mot de passe actuel n'est pas le bon";
                
            }

         }
        return $this->render('account/password.html.twig',[
            'form' =>$form->createView(),
            'notification' => $notification
        ]);
    }
}
