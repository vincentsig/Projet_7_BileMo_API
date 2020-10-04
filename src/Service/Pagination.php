<?php

namespace App\Service;

use Pagerfanta\Pagerfanta;
use Pagerfanta\Doctrine\ORM\QueryAdapter;
use Symfony\Component\HttpFoundation\Request;
use Hateoas\Representation\PaginatedRepresentation;
use Hateoas\Representation\CollectionRepresentation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;


class Pagination
{
    public function findPaginatedList(ServiceEntityRepository $repo, Request $request, int $companyId = null)
    {

        $page = $request->query->getInt('page', 1);
        $limit = $request->query->getInt('limit', 10);
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
