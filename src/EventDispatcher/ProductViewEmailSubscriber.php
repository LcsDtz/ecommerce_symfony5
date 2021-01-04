<?php

namespace App\EventDispatcher;

use Psr\Log\LoggerInterface;
use App\Event\ProductViewEvent;
use Symfony\Component\EventDispatcher\EventDispatcher;

class ProductViewEmailSubscriber extends EventDispatcher
{
    protected $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public static function getSubscribedEvents()
    {
        return [
            'product.view' => 'sendEmail',
        ];
    }

    public function sendEmail(ProductViewEvent $productViewEvent)
    {
        $this->logger->info("Email envoyé à l'admin pour le produit" .
            $productViewEvent->getProduct()->getId());
    }
}
