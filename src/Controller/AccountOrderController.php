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
        
    #[Route('/compte/mes-commandes-en-cours', name: 'account_order_in_process')]
    public function index_in_process(OrderRepository $order_repo): Response
    {
        // $orders = $order_repo->findBySucessOrders($this->getUser());
        $orders = $this->entityManager->getRepository(Order::class)->findSuccessOrders($this->getUser());

        return $this->render('account/order_in_process.html.twig', [
            'orders' => $orders
        ]);
    }

    #[Route('/compte/mes-commandes-terminées', name: 'account_order_done')]
    public function index_order_done(OrderRepository $order_repo): Response
    {
        // $orders = $order_repo->findBySucessOrders($this->getUser());
        $orders = $this->entityManager->getRepository(Order::class)->findOrdersDone($this->getUser());

        return $this->render('account/order_done.html.twig', [
            'orders' => $orders
        ]);
    }

    #[Route('/compte/mes-commandes-en-cours/{reference}', name: 'account_order_in_process_show')]
    public function show_order_in_process($reference, OrderRepository $order_repo): Response
    {
        $order = $order_repo->findOneByReference($reference);
        // $order = $this->entityManager->getRepository(Order::class)->findOneByReference($reference);

        if (!$order || $order->getUser() != $this->getUser()) {
            return $this->redirectToRoute('account_order');
        }
        return $this->render('account/order_in_process_show.html.twig', [
            'order' => $order
        ]);
    }

    #[Route('/compte/mes-commandes-terminées/{reference}', name: 'account_order_done_show')]
    public function show_order_done($reference, OrderRepository $order_repo): Response
    {
        $order = $order_repo->findOneByReference($reference);
        // $order = $this->entityManager->getRepository(Order::class)->findOneByReference($reference);

        if (!$order || $order->getUser() != $this->getUser()) {
            return $this->redirectToRoute('account_order');
        }
        return $this->render('account/order_done_show.html.twig', [
            'order' => $order
        ]);
    }
}
