<?php

namespace App\Service;

use Pagerfanta\Pagerfanta;
use Doctrine\ORM\QueryBuilder;
use Pagerfanta\Doctrine\ORM\QueryAdapter;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Hateoas\Representation\PaginatedRepresentation;
use Hateoas\Representation\CollectionRepresentation;


class Pagination
{
    private $security;
    private $cache;

    public function __construct(Security $security, CacheInterface $cache)
    {
        $this->security = $security;
        $this->cache = $cache;
    }

    public function getPaginationOrCache(QueryBuilder $queryList, Request $request)
    {
        $routeName = $request->attributes->get('_route');
        $companyId = $this->security->getUser()->getId();
        $page = $request->query->get('page', 1);
        $limit = $request->query->get('limit', 10);

        $pagination =  $this->cache->get($routeName . '-id-' . $companyId . 'page-' . $page . 'limit-' . $limit, function (ItemInterface $item) use ($routeName, $page, $limit, $queryList) {
            sleep(2);
            return   $this->findPaginatedList($routeName, $page, $limit, $queryList);
        });

        return $pagination;
    }

    private function findPaginatedList($routeName, $page, $limit, QueryBuilder $queryList): PaginatedRepresentation
    {

        $adapter = new QueryAdapter($queryList);
        $paginator = new Pagerfanta($adapter);

        $paginator->setMaxPerPage($limit);
        $paginator->setCurrentPage($page);

        $paginatedCollection =  new PaginatedRepresentation(
            new CollectionRepresentation($paginator->getCurrentPageResults()),
            $routeName,
            array(),
            $paginator->getCurrentPage(),
            $paginator->getMaxPerPage(),
            $paginator->getNbPages(),
            'page',
            'limit',
            true,
            $paginator->getNbResults()
        );
        $paginator->getCurrentPageResults();

        return $paginatedCollection;
    }
}
