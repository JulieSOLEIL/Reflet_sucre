<?php

namespace App\Controller;

use App\Repository\HeaderRepository;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/', name: 'home')]
    public function index(ProductRepository $prod_repo, HeaderRepository $header_repo): Response
    {
        $products = $prod_repo->findByIsBest(1);
        $headers = $header_repo->findAll();
        
        return $this->render('home/index.html.twig', [
            'products' => $products,
            'headers' => $headers
        ]);
    }
    #[Route('/qui-sommes-nous', name: 'home_about')]
    public function about(): Response
    {     
        return $this->render('home/about.html.twig', [
        ]);
    }
    #[Route('/contact', name: 'home_contact')]
    public function contact(): Response
    {       
        return $this->render('home/contact.html.twig', [
        ]);
    }
}
