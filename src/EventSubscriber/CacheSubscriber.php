<?php

namespace App\EventSubscriber;

use App\Entity\User;
use App\Entity\Phone;
use Doctrine\ORM\Events;
use Doctrine\Common\EventSubscriber;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Psr\Cache\CacheItemPoolInterface;

class CacheSubscriber implements EventSubscriber
{
    private $doctrineUserCachePool;

    public function __construct(CacheItemPoolInterface $doctrineUserCachePool, CacheItemPoolInterface $doctrinePhoneCachePool)
    {
        $this->doctrineUserCachePool = $doctrineUserCachePool;
        $this->doctrinePhoneCachePool = $doctrinePhoneCachePool;
    }

    public function getSubscribedEvents()
    {
        return [
            Events::postPersist,
            Events::postRemove,
        ];
    }

    public function postPersist(LifecycleEventArgs $args)
    {
        $this->invalidCache($args);
    }

    public function postRemove(LifecycleEventArgs $args)
    {
        $this->invalidCache($args);
    }

    private function invalidCache(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();

        if ($entity instanceof Phone) {
            $this->doctrinePhoneCachePool->Clear();
        }

        if ($entity instanceof User) {
            $this->doctrineUserCachePool->clear();
        }
    }
}
