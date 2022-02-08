<?php

namespace App\Controller;

use App\Entity\Order;
use App\Repository\OrderRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AccountOrderController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        return $this->entityManager = $entityManager;
    }
        
    #[Route('/compte/mes-commandes', name: 'account_order')]
    public function index(OrderRepository $order_repo): Response
    {
        // $orders = $order_repo->findBySucessOrders($this->getUser());
        $orders = $this->entityManager->getRepository(Order::class)->findSuccessOrders($this->getUser());

        return $this->render('account/order.html.twig', [
            'orders' => $orders
        ]);
    }

    #[Route('/compte/mes-commandes/{reference}', name: 'account_order_show')]
    public function show($reference, OrderRepository $order_repo): Response
    {
        $order = $order_repo->findOneByReference($reference);
        // $order = $this->entityManager->getRepository(Order::class)->findOneByReference($reference);

        if (!$order || $order->getUser() != $this->getUser()) {
            return $this->redirectToRoute('account_order');
        }
        return $this->render('account/order_show.html.twig', [
            'order' => $order
        ]);
    }
}
