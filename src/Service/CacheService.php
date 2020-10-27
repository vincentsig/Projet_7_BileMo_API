<?php

// src/AppBundle/Service/CacheExample.php

namespace App\Service;

use Psr\Cache\CacheItemPoolInterface;

class CacheExample
{
    /**
     * @var CacheItemPoolInterface
     */
    private $cache;

    public function __construct(CacheItemPoolInterface $cache, $cacheKey)
    {
        $this->cache = $cache;
        $this->key = $cacheKey;
    }

    public function get(string $cacheKey)
    {




        // either way, this is now the result we want

    }
}
