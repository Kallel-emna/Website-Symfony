<?php

namespace App\EventListener;

use App\Entity\Produit;
use App\Notification\LowQuantityNotification;
use Doctrine\ORM\Event\OnFlushEventArgs;
use Symfony\Component\Notifier\NotifierInterface;

class ProductUpdateListener
{
    private NotifierInterface $notifier;

    public function __construct(NotifierInterface $notifier)
    {
        $this->notifier = $notifier;
    }

    public function onFlush(OnFlushEventArgs $eventArgs): void
    {
        $entityManager = $eventArgs->getEntityManager();
        $unitOfWork = $entityManager->getUnitOfWork();

        foreach ($unitOfWork->getScheduledEntityUpdates() as $entity) {
            if (!$entity instanceof Produit) {
                continue;
            }

            /** @var Produit $produit */
            $produit = $entity;

            if ($produit->isLowQuantity()) {
                $notification = new LowQuantityNotification($produit->getNomProduit());
                $this->notifier->send($notification);
            }
        }
    }
}


?>