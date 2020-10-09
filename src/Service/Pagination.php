<?php

namespace App\Service;

use Pagerfanta\Pagerfanta;
use Pagerfanta\Doctrine\ORM\QueryAdapter;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Hateoas\Representation\PaginatedRepresentation;
use Hateoas\Representation\CollectionRepresentation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

class Pagination
{
    private $security;
    private $cache;

    public function __construct(Security $security, CacheInterface $cache)
    {
        $this->security = $security;
        $this->cache = $cache;
    }

    public function getPaginationOrCache(ServiceEntityRepository $repo, Request $request)
    {

        $companyId = $this->security->getUser()->getId();
        $page = $request->query->getInt('page', 1);
        $limit = $request->query->getInt('limit', 10);

        $pagination =  $this->cache->get('id-' . $companyId . 'page-' . $page . 'limit-' . $limit, function (ItemInterface $item) use ($request, $repo, $page, $limit, $companyId) {
            sleep(10);
            return   $this->findPaginatedList($repo, $request, $page, $limit, $companyId);
        });

        return $pagination;
    }

    private function findPaginatedList(ServiceEntityRepository $repo, Request $request, $page, $limit,  $companyId = 0)
    {

        $route = $request->attributes->get('_route');

        $queryBuilder =  $repo->ListQueryBuilder($companyId);

        $adapter = new QueryAdapter($queryBuilder);
        $paginator = new Pagerfanta($adapter);

        $paginator->setMaxPerPage($limit);
        $paginator->setCurrentPage($page);

        $paginatedCollection =  new PaginatedRepresentation(
            new CollectionRepresentation($paginator->getCurrentPageResults()),
            $route,
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
