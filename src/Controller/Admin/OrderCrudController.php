<?php

namespace App\Controller\Admin;

use App\Entity\Order;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use Symfony\Component\Console\Color;

class OrderCrudController extends AbstractCrudController
{
    private $entityManager;
    private $adminUrlGenerator;

    public function __construct(EntityManagerInterface $entityManager, AdminUrlGenerator $adminUrlGenerator)
    {
        $this->entityManager = $entityManager;
        $this->adminUrlGenerator = $adminUrlGenerator;
    }
    public static function getEntityFqcn(): string
    {
        return Order::class;
    }
    
    public function configureActions(Actions $actions): Actions
    {
        $updatePreparation = Action::new('updatePreparation', 'Préparation en cours', 'fas fa-truck')->linkToCrudAction('updatePreparation');
        $updateDelivery = Action::new('updateDelivery', 'Livraison en cours', 'fas fa-box')->linkToCrudAction('updateDelivery');
        $deliveryDone = Action::new('deliveryDone', 'Livraison terminée', 'fas fa-check')->linkToCrudAction('deliveryDone');
        $updateReturn = Action::new('updateReturn', 'Retour en cours', 'fas fa-clock')->linkToCrudAction('updateReturn');
        $returnDone = Action::new('returnDone', 'Retour effectué', 'fas fa-check')->linkToCrudAction('returnDone');
        $updateRefund = Action::new('updateRefund', 'Remboursement en cours', 'fas fa-money-bill')->linkToCrudAction('updateRefund');
        $refundDone = Action::new('refundDone', 'Remboursement effectué', 'fas fa-money-check')->linkToCrudAction('refundDone');

        return $actions
        ->add('detail', $updatePreparation)
        ->add('detail', $updateDelivery)
        ->add('detail', $deliveryDone)
        ->add('detail', $updateReturn)
        ->add('detail', $returnDone)
        ->add('detail', $updateRefund)
        ->add('detail', $refundDone)
        ->add('index', 'detail');
    }

    public function updatePreparation(AdminContext $context)
    {
        $order = $context->getEntity()->getInstance();
        $order->setState(2);
        $this->entityManager->flush();

        $this->addFlash('notice', "<span style='color:orange;'><strong>La commande".$order->getReference()." est bien <u>en cours de préparation</u></strong></span>");

        $url = $this->adminUrlGenerator
        ->setController(OrderCrudController::class)
        ->setAction('index')
        ->generateUrl();

        return $this->redirect($url);
    }

    public function updateDelivery(AdminContext $context)
    {
        $order = $context->getEntity()->getInstance();
        $order->setState(3);
        $this->entityManager->flush();

        $this->addFlash('notice', "<span style='color:orangered;'><strong>La commande".$order->getReference()." est bien <u>en cours de livraison</u></strong></span>");

        $url = $this->adminUrlGenerator
        ->setController(OrderCrudController::class)
        ->setAction('index')
        ->generateUrl();

        return $this->redirect($url);
    }

    public function deliveryDone(AdminContext $context)
    {
        $order = $context->getEntity()->getInstance();
        $order->setState(4);
        $this->entityManager->flush();

        $this->addFlash('notice', "<span style='color:green;'><strong>La livraison de la commande".$order->getReference()." est <u>terminée</u></strong></span>");

        $url = $this->adminUrlGenerator
        ->setController(OrderCrudController::class)
        ->setAction('index')
        ->generateUrl();

        return $this->redirect($url);
    }

    public function updateReturn(AdminContext $context)
    {
        $order = $context->getEntity()->getInstance();
        $order->setState(5);
        $this->entityManager->flush();

        $this->addFlash('notice', "<span style='color:blue;'><strong>En attente du retour de la commande ".$order->getReference()." dans notre entrepôt</strong>.</span>");

        $url = $this->adminUrlGenerator
        ->setController(OrderCrudController::class)
        ->setAction('index')
        ->generateUrl();

        return $this->redirect($url);
    }

    public function returnDone(AdminContext $context)
    {
        $order = $context->getEntity()->getInstance();
        $order->setState(6);
        $this->entityManager->flush();

        $this->addFlash('notice', "<span style='color:lightgreen;'><strong>Le retour de la commande ".$order->getReference()." a été effectué</strong>.</span>");

        $url = $this->adminUrlGenerator
        ->setController(OrderCrudController::class)
        ->setAction('index')
        ->generateUrl();

        return $this->redirect($url);
    }

    public function updateRefund(AdminContext $context)
    {
        $order = $context->getEntity()->getInstance();
        $order->setState(7);
        $this->entityManager->flush();

        $this->addFlash('notice', "<span style='color:lightblue;'><strong>Le remboursement de la commande ".$order->getReference()." est en cours</strong>.</span>");

        $url = $this->adminUrlGenerator
        ->setController(OrderCrudController::class)
        ->setAction('index')
        ->generateUrl();

        return $this->redirect($url);
    }

    public function refundDone(AdminContext $context)
    {
        $order = $context->getEntity()->getInstance();
        $order->setState(8);
        $this->entityManager->flush();

        $this->addFlash('notice', "<span style='color:green;'><strong>Le remboursement de la commande ".$order->getReference()." a été effectué</strong>.</span>");

        $url = $this->adminUrlGenerator
        ->setController(OrderCrudController::class)
        ->setAction('index')
        ->generateUrl();

        return $this->redirect($url);
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud->setDefaultSort(['id' => 'DESC']);
    }
    
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            DateTimeField::new('createdAt', 'Commandée le'),
            TextField::new('user.getFullName', 'Utilisateur'),
            TextEditorField::new('delivery', 'Adresse de livraison')->formatValue(function ($value) { return $value; })->onlyOnDetail(),
            MoneyField::new('total', 'Total produit')->setCurrency('EUR'),
            TextField::new('carrierName', 'Transporteur'),
            MoneyField::new('carrierPrice', 'Frais de port')->setCurrency('EUR'),
            ChoiceField::new('state', 'Etat statut')->setChoices([
                'Non réglée' => 0,
                'Reglée' => 1,
                'Préparation en cours' => 2,
                'Livraison en cours' => 3,
                'Livraison terminée' => 4,
                'Retour en cours' => 5,
                'Retour effectué' => 6,
                'Remboursement en cours' => 7,
                'Remboursement effectué' => 8
            ]),
            ArrayField::new('orderDetails', 'Produits achetés')->hideOnIndex()
            
        ];
    }
}
