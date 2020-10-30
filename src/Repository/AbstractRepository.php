<?php

namespace App\Repository;

use Doctrine\ORM\Query;
use Pagerfanta\Pagerfanta;
use Pagerfanta\Doctrine\ORM\QueryAdapter;
use Symfony\Component\Serializer\Exception\InvalidArgumentException;

trait AbstractRepository
{
    /**
     * @param Query $qb 
     * @param mixed $page 
     * @param mixed $limit 
     * @return Pagerfanta 
     * @throws InvalidArgumentException
     */
    protected function paginate(Query $qb, $page, $limit): Pagerfanta
    {
        $this->isValidParameters($page, $limit);

        $paginator = new Pagerfanta(new QueryAdapter($qb), $page, $limit);

        $paginator->setMaxPerPage($limit);
        $paginator->setCurrentPage($page);

        return $paginator;
    }

    /**
     * @param string $page 
     * @param string $limit 
     * @return bool 
     * @throws InvalidArgumentException 
     */
    private function isValidParameters(string $page, string $limit): bool
    {

        if ((is_numeric($page)) && is_numeric($limit)) {
            return true;
        }
        throw new InvalidArgumentException('Bad Request: The page and the limit parameters must be numeric');
    }
}
