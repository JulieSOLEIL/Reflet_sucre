<?php

namespace App\Controller;

use App\Classe\Mail;
use App\Entity\User;
use App\Form\RegisterType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class RegisterController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $emi) {
        $this->entityManager = $emi;
    }
    
    #[Route('/inscription', name: 'register')]
    public function index(Request $request, UserPasswordHasherInterface $hasher, UserRepository $user_repo): Response
    {
        $notification = null;

        $user = new User();
        $form = $this->createForm(RegisterType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $user = $form->getData(); 

            $search_email = $user_repo->findOneByEmail($user->getEmail());

            if (!$search_email) {
                $psw = $hasher->hashPassword($user, $user->getPassword());

                $user->setPassword($psw);

                $this->entityManager->persist($user); //persist signifie vouloir figer les datas de $user
                $this->entityManager->flush();

                $mail = new Mail();
                $content = 'Bonjour '.$user->getFirstname().'.'.'<br>'.'Bienvenue sur le site Reflet Sucré, un site dédiée aux pâtisseries de Julie.'.'<br>'.'Vous pouvez dès à présent découvrir nos pâtisseries et gâteaux sur notre plateforme. Commandez-les sans attendre ! ';
                $mail->Send($user->getEmail(), $user->getFirstname(), 'Bienvenue sur le site Reflet Sucré', $content);

                $notification = 'Votre inscription s\'est correctement déroulée. Vous pouvez dès à présent vous connecter à votre compte.';
            } else {
                $notification = 'L\'email que vous avez renseigné existe déjà. Merci de renseigner un email non existant.';
            }
            
            
        }


        return $this->render('register/index.html.twig', [
            'form' => $form->createView(),
            'notification' => $notification
        ]);
    }
}
