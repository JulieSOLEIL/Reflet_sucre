<?php

namespace App\Controller;

use App\Form\ChangePasswordType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

class AccountPasswordController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $emi) {
        $this->entityManager = $emi;
    }
    #[Route('/compte-modifier-mot-de-passe', name: 'account_password')]
    public function index(Request $request, UserPasswordHasherInterface $hasher): Response
    {
        $notif = null;

        $user = $this->getUser();
        $form = $this->createForm(ChangePasswordType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $old_psw = $form->get('old_password')->getData();

            if ($hasher->isPasswordValid($user, $old_psw)) {
                $new_psw = $form->get('new_password')->getData();
                $psw = $hasher->hashPassword($user, $new_psw);

                $user->setPassword($psw);
                $this->entityManager->flush();

                $notif = 'Votre mot de passe a bien été mise à jour';
            } else {
                $notif = 'Votre mot de passe actuel n\'est pas le bon';
            }
        }

        return $this->render('account/password.html.twig', [
            'form' => $form->createView(),
            'notif' => $notif
        ]);
    }
}
