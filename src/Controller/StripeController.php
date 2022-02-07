<?php

namespace App\Controller;

use Stripe\Stripe;
use App\Classe\Cart;
use App\Entity\Order;
use App\Entity\Product;
use Stripe\ApiOperations\Create;
use Stripe\Checkout\Session;
use App\Repository\OrderRepository;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class StripeController extends AbstractController
{
    #[Route('/commande/create-session/{reference}', name: 'stripe_create_session')]
    public function index(Cart $cart, $reference, EntityManagerInterface $entityManager, OrderRepository $order_repo, ProductRepository $prod_repo): Response
    {
        $products_for_stripe = [];
        $YOUR_DOMAIN = 'http://localhost:8000';

        $order = $order_repo->findOneByReference($reference);

        if (!$order) {
            new JsonResponse(['error' => 'order']);
            // return $this->redirectToRoute('order');
        }

        foreach ($order->getOrderDetails()->getValues() as $product) {
            $product_object = $prod_repo->findOneByName($product->getProduct());

            $products_for_stripe[] = [
                'price_data' => [
                    'unit_amount' => $product->getPrice(),
                    'currency' => 'eur',
                    'product_data' => [
                        'name' => $product->getProduct(),
                        'images' => [$YOUR_DOMAIN.'/uploads/'.$product_object->getIllustration()],
                    ],
                ],  
                'quantity' => $product->getQuantity(),
            ];
           
        }
        // Prix livraison : 
        $products_for_stripe[] = [
            'price_data' => [
                'unit_amount' => $order->getCarrierPrice(),
                'currency' => 'eur',
                'product_data' => [
                    'name' => $order->getCarrierName(),
                    'images' => [$YOUR_DOMAIN],
                ],
            ],  
            'quantity' => 1,
        ];

        $stripe = Stripe::setApiKey("sk_test_51KP1kIAIeuFpHgfhHPCKsoBnNT29Vuf3f0uIa8RsZsuBfM8yarXk7k92srlXs4PsMCmRNCVdIpbTM5SlRcp0XTqk00RV9r2T8h");

        // dd($stripe);

        $checkout_session = Session::Create([
            'customer_email' => $this->getUser()->getEmail(),
            'payment_method_types' => [
                'card'
            ], 
            'line_items' => [
                $products_for_stripe
            ],
            'mode' => 'payment',
            'success_url' => $YOUR_DOMAIN . '/commande/merci/{CHECKOUT_SESSION_ID}',
            'cancel_url' => $YOUR_DOMAIN . '/commande/erreur/{CHECKOUT_SESSION_ID}',
        ]);

        
        $order->setStripeSessionId($checkout_session->id);
        $entityManager->flush();

        return $this->redirect($checkout_session->url);
        // $response = new JsonResponse(['id' => $checkout_session->id]);
        // return $response;
    }
}
