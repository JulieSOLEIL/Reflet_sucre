<?php

namespace App\Controller;

use DateTime;
use App\Classe\Mail;
use App\Entity\ResetPassword;
use App\Form\ResetPasswordType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ResetPasswordRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class ResetPasswordController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/mot-de-passe-oublié', name: 'reset_password')]
    public function index(Request $request, UserRepository $user_repo): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('home');
        }

        if ($request->get('email')) {
            $user = $user_repo->findOneByEmail($request->get('email'));

            if ($user) {
                // 1: Demander enregistrer dans la base la demande de reset_password avec user, token, createdAt:
                $reset_psw = new ResetPassword();
                $reset_psw->setUser($user);
                $reset_psw->setToken(uniqid());
                $reset_psw->setCreatedAt(new \DateTime());
                $this->entityManager->persist($reset_psw);
                $this->entityManager->flush();

                //2: envoie email à l'user avec lien pour réinitialiser psw:
                $url = $this->generateUrl('update_password', [
                    'token' => $reset_psw->getToken()
                ]);

                $content = 'Bonjour' . $user->getFirstname() . ',' . '<br>Vous avez demandé une réinitialisation de votre mot de passe sur le site Reflet Sucré.<br><br>';
                $content .= 'Merci de bien vouloir cliquer sur le lien afin de <a href="' . $url . '">mettre à jour votre mot de passe</a>.';

                $mail = new Mail();
                $mail->send($user->getEmail(), $user->getFirstname() . ' ' . $user->getLastname(), 'Réinitialiser votre mot de passe sur Reflet Sucré', $content);

                $this->addFlash('notice', 'Vous allez recevoir dans quelques instants un email avec un lien pour réinitialiser votre mot de passe.');
            } else {
                $this->addFlash('notice', 'Cette adresse email est inconnue.');
            }
        }
        return $this->render('reset_password/index.html.twig', []);
    }

    #[Route('/modifier-mon-mot-de-passe/{token}', name: 'update_password')]
    public function update($token, ResetPasswordRepository $resetPsw, Request $request, UserPasswordHasherInterface $hasher): Response
    {
        $reset_psw = $resetPsw->findOneByToken($token);

        if (!$reset_psw) {
            return $this->redirectToRoute('reset_password');
        }

        // vérifier si le createdAt = maintenant - 15min:
        $now = new \DateTime();

        if ($now > $reset_psw->getCreatedAt()->modify('+ 15min')) {
            $this->addFlash('notice', 'Votre demande de réinitialisation du mot de passe a expiré, merci de la renouveller.');

            return $this->redirectToRoute('reset_password');
        }

        //rendre une vue avec mdp et confirmation mdp :
        $form = $this->createForm(ResetPasswordType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $new_psw = $form->get('new_password')->getData();

            // encodage des mdp :
            $psw = $hasher->hashPassword($reset_psw->getUser(), $new_psw);

            $reset_psw->getUser()->setPassword($psw);

            //flush en database :
            $this->entityManager->flush();

            // rediction de l'user vers page de connexion :
            $this->addFlash('notice', 'Votre mot de passe à bien été mis à jour.');
            return $this->redirectToRoute('app_login');

        }

        return $this->render('reset_password/update.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
