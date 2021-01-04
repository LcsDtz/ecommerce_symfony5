<?php

namespace App\Controller\Purchase;

use App\Entity\Purchase;
use App\Cart\CartService;
use App\Event\PurchaseSuccessEvent;
use App\Repository\PurchaseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PurchasePaymentSuccessController extends AbstractController
{
    /**
     * @Route("/purchase/success/{id}", name="purchase_payment_success")
     * @IsGranted("ROLE_USER")
     */
    public function success($id, PurchaseRepository $purchaseRepository, EntityManagerInterface $em, CartService $cartService, EventDispatcherInterface $dispatcher)
    {
        $purchase = $purchaseRepository->find($id);

        if (
            !$purchase ||
            ($purchase && $purchase->getUser() !== $this->getUser()) ||
            ($purchase && $purchase->getStatus() === Purchase::STATUS_PAID)
        ) {
            $this->addFlash('warning', "La commande n'existe pas.");
            return $this->redirectToRoute('purchase_index');
        }

        $purchase->setStatus(Purchase::STATUS_PAID);
        $em->flush();

        $cartService->empty();

        $purchaseEvent = new PurchaseSuccessEvent($purchase);
        $dispatcher->dispatch($purchaseEvent, 'purchase.success');

        $this->addFlash('success', "La commande a bien été payée et confirmée");
        return $this->redirectToRoute('purchase_index');
    }
}
