<?php

namespace App\Controller;

use App\Classe\Cart;
use Stripe\Stripe;
use App\Entity\Order;
use App\Entity\Product;
use Stripe\Checkout\Session;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

class StripeController extends AbstractController
{
    #[Route('/commande/create-session/{reference}', name: 'stripe_create_session')]
    public function index(EntityManagerInterface $entityManager,Cart $cart,$reference): Response
    {

            $product_for_stripe = [];
            $YOUR_DOMAIN = 'http://127.0.0.1:8000';
            $order = $entityManager->getRepository(Order::class)->findOneByReference($reference);
            
            if (!$order)
            {
                 return $this->redirectToRoute('order');

            }

           // dd($order->getOrderDetails()->getValues());
            foreach ($order->getOrderDetails()->getValues() as $product) {
                //dd($product);

                $product_object = $entityManager->getRepository(Product::class)->findOneByName( $product->getProduct());
                $product_for_stripe[] = [
                    'price_data' =>[
                        'currency' => 'eur',
                        'unit_amount' => $product->getPrice(),
                        'product_data' => [
                            'name' => $product->getProduct(),
                            'images' => [$YOUR_DOMAIN."/uploads/".$product_object->getIllustration()],
                        ]
                    ],
                    'quantity' =>  $product->getQuantity(),

                ];

            }
                // for carrier
                $product_for_stripe[] = [
                    'price_data' =>[
                        'currency' => 'eur',
                        'unit_amount' => $order->getCarrierPrice() * 100,
                        'product_data' => [
                            'name' => $order->getCarrierName(),
                            'images' => [$YOUR_DOMAIN],
                        ]
                    ],
                    'quantity' => 1,

                ];

           // dd($product_for_stripe);

            Stripe::setApiKey('sk_test_51MZWbzFCACOuR4a276GGs5BmDom2CKMxFsBNqoQWVaPcooBUi3cmbZzCVRp7UbaCtwCzOA6C0tYS3BTWOxQaTssq00q3waAQKs');
            $checkout_session = Session::create([
            'customer_email' => $this->getUser()->getEmail(),
            'line_items' => [
                $product_for_stripe
            ],
            'payment_method_types' => ['card'],
            'mode' => 'payment',
            'success_url' => $YOUR_DOMAIN . '/commande/merci/{CHECKOUT_SESSION_ID}',
            'cancel_url' => $YOUR_DOMAIN . '/commande/erreur/{CHECKOUT_SESSION_ID}',
            ]);
            
           // $response = new JsonResponse(['id' => $checkout_session->id]);
            //return $response;
            $order->setStripeSessionId($checkout_session->id);
            $entityManager->flush();

            return $this->redirect($checkout_session->url);
    }
}
