<?php

namespace App\Controller;

use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{

    private $entityManger;

    public  function __construct(EntityManagerInterface $entityManger) {
        $this->entityManger = $entityManger;

    }

    #[Route('/nos-produits', name: 'products')]
    public function index(EntityManagerInterface $entityManger): Response
    {

        $products = $this->entityManger->getRepository(Product::class)->findAll();

        return $this->render('product/index.html.twig',[
            'products' => $products
        
        ]);
    }
}
