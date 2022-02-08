<?php

namespace App\Controller;

use App\Repository\OrderRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class OrderCancelController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    #[Route('/commande/erreur/{stripeSessionId}', name: 'order_cancel')]
    public function index($stripeSessionId, OrderRepository $order_repo): Response
    {
        $order = $order_repo->findOneByStripeSessionId($stripeSessionId);
        // $order = $this->entityManager->getRepository(Order::class)->findOneByStripeSessionId($stripeSessionId);

        // dd($order);
        
        if (!$order || $order->getUser() != $this->getUser()) {
            return $this->redirectToRoute('home');
        }
        //envoyer email au client pour indiquer echec de paiement
        return $this->render('order_cancel/index.html.twig', [
            'order' => $order
        ]);
    }
}
