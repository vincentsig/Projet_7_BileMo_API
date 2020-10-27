<?php

namespace App\Repository;

use Pagerfanta\Pagerfanta;
use Pagerfanta\Doctrine\ORM\QueryAdapter;
use Symfony\Component\Serializer\Exception\InvalidArgumentException;

trait AbstractRepository
{
    protected function paginate($qb, $page, $limit): Pagerfanta
    {
        $this->isValidParameters($page, $limit);

        $paginator = new Pagerfanta(new QueryAdapter($qb), $page, $limit);

        $paginator->setMaxPerPage($limit);
        $paginator->setCurrentPage($page);

        return $paginator;
    }

    private function isValidParameters($page, $limit): bool
    {

        if ((is_numeric($page)) && is_numeric($limit)) {
            return true;
        }
        throw new InvalidArgumentException('Bad Request: The page and the limit parameters must be numeric');
    }
}
