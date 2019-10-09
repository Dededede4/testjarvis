<?php

namespace App\EventListener;

use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use Psr\Log\LoggerInterface;
use App\Entity\User;

class Logger
{
    protected $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function postPersist(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();

        // only act on some "User" entity
        if (!$entity instanceof User) {
            return;
        }

        $this->logger->info('PostPersist user #'.$entity->getId());
    }
}