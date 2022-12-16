<?php

namespace App\Controller;

use App\Classe\Searche;
use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\SearcheType;
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
        $searche = new Searche();
        $form = $this->createForm(SearcheType::class, $searche);
        
        $products = $this->entityManger->getRepository(Product::class)->findAll();

        return $this->render('product/index.html.twig',[
            'products' => $products,
            'form' => $form->createView(),
        
        ]);
    }


    #[Route('/produit/{slug}', name: 'product')]
    public function show($slug): Response
    {

        $product = $this->entityManger->getRepository(Product::class)->findOneBySlug($slug);
        
        if(!$product) {
            return $this->redirectToRoute('products');

        }
        return $this->render('product/show.html.twig',[
            'product' => $product
        
        ]);
    }
}
