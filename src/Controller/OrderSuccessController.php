<?php

namespace App\Controller;

use App\Classe\Cart;
use App\Entity\Order;
use App\Repository\OrderRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class OrderSuccessController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/commande/merci/{stripeSessionId}', name: 'order_success')]
    public function index($stripeSessionId, OrderRepository $order_repo, Cart $cart): Response
    {
        $order = $order_repo->findOneByStripeSessionId($stripeSessionId);
        // $order = $this->entityManager->getRepository(Order::class)->findOneByStripeSessionId($stripeSessionId);

        // dd($order);
        
        if (!$order || $order->getUser() != $this->getUser()) {
            return $this->redirectToRoute('home');
        }

        if (!$order->getIsPaid()) {
            //vider la session 'cart'
            $cart->remove();
            //modifier statut isPaid de la commande en mettant 1(boolean).
            $order->setIsPaid(1);
            $this->entityManager->flush();

            //Envoyer email au client pour confirmation commande
        }
        // afficher infos de la commande du client
        return $this->render('order_success/index.html.twig', [
            'order' => $order
        ]);
    }
}
