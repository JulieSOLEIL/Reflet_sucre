<?php

namespace App\Controller;

use Stripe\Stripe;
use App\Classe\Cart;
use App\Entity\Order;
use App\Entity\Product;
use Stripe\Checkout\Session;
use Stripe\ApiOperations\Create;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class StripeController extends AbstractController
{
    #[Route('/commande/create-session/{reference}', name: 'stripe_create_session')]
    public function index(Cart $cart, $reference, EntityManagerInterface $entityManager): Response
    {
        $products_for_stripe = [];
        $YOUR_DOMAIN = 'http://127.0.0.1:8000';

        $order = $entityManager->getRepository(Order::class)->findOneByReference($reference);

        if (!$order) {
            new JsonResponse(['error' => 'order']);
        }

        foreach ($order->getOrderDetails()->getValues() as $product) {
            $product_object = $entityManager->getRepository(Product::class)->findOneByName($product->getProduct());

            $products_for_stripe[] = [
                'price_data' => [
                    'unit_amount' => $product->getPrice(),
                    'currency' => 'eur',
                    'product_data' => [
                        'name' => $product->getProduct(),
                        'images' => [$YOUR_DOMAIN.'uploads/'.$product_object->getIllustration()],
                    ],
                ],  
                'quantity' => $product->getQuantity(),
            ];
           
        }
        // Prix livraison : 
        $products_for_stripe[] = [
            'price_data' => [
                'unit_amount' => $order->getCarrierPrice() * 100,
                'currency' => 'eur',
                'product_data' => [
                    'name' => $order->getCarrierName(),
                    'images' => [$YOUR_DOMAIN],
                ],
            ],  
            'quantity' => 1,
        ];

        \Stripe\Stripe::setApiKey("sk_test_51KP1kIAIeuFpHgfhHPCKsoBnNT29Vuf3f0uIa8RsZsuBfM8yarXk7k92srlXs4PsMCmRNCVdIpbTM5SlRcp0XTqk00RV9r2T8h");

        $checkout_session = Session::create([
            'customer_email' => $this->getUser()->getEmail(),
            'payment_method_types' => [
                'card'
            ],
            'line_items' => [
                $products_for_stripe
            ],
            'mode' => 'payment',
            'success_url' => $YOUR_DOMAIN . '/success.html',
            'cancel_url' => $YOUR_DOMAIN . '/cancel.html',
        ]);

        // dump($checkout_session->id);
        // dd($checkout_session);

        // header("HTTP/1.1 303 See Other");
        // header("Location: " . $checkout_session->url);
        return $this->redirect($checkout_session->url);
        // $response = new JsonResponse(['id' => $checkout_session->id]);
        // return $response;
    }
}
