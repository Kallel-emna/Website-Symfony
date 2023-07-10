<?php
namespace App\Notification;

use Symfony\Component\Notifier\Notification\Notification;
use Symfony\Component\Notifier\Recipient\RecipientInterface;

class LowQuantityNotification extends Notification
{
    public function __construct(string $nom_produit)
    {
        parent::__construct('Alerte de faible quantité', ['nom_produit' => $nom_produit]);
    }

    public function getChannels(RecipientInterface $recipient): array
    {
        return ['browser'];
    }
}

?>