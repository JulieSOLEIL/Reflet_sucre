<?php

namespace App\Controller;

use App\Classe\Cart;
use App\Classe\Mail;
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
        // si la commande est non réglée alors tu fais :
        if (!$order->getState(0)) {
            //vider la session 'cart'
            $cart->remove();
            //modifier statut de la commande en mettant 1(réglée).
            $order->setState(1);
            $this->entityManager->flush();

            //Envoyer email au client pour confirmation commande
            $mail = new Mail();
            $content = 'Bonjour '.$order->getUser()->getFirstname().'.'.'<br>'.'Merci pour votre commande.'.'<br>'.'Vous trouverez le suivi de votre commande dans votre compte client. N\'hésitez pas à poser vos questions sur'.'<a href="{{ path("order_success")}}">'.' contact@reflet-sucré.fr'.'</a>'.', nous vous y répondrons rapidement.';
            $mail->Send($order->getUser()->getEmail(), $order->getUser()->getFirstname(), 'Votre commande sur Reflet Sucré a été validé !', $content);
        }
        // afficher infos de la commande du client
        return $this->render('order_success/index.html.twig', [
            'order' => $order
        ]);
    }
}
