<?php

namespace App\Repository;

use App\Entity\Phone;
use Psr\Cache\CacheItemPoolInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Contracts\Cache\ItemInterface;
use Hateoas\Representation\PaginatedRepresentation;
use Hateoas\Representation\CollectionRepresentation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Pagerfanta\Pagerfanta;

/**
 * @method Phone|null find($id, $lockMode = null, $lockVersion = null)
 * @method Phone|null findOneBy(array $criteria, array $orderBy = null)
 * @method Phone[]    findAll()
 * @method Phone[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PhoneRepository extends ServiceEntityRepository
{
    use AbstractRepository;

    private $doctrinePhoneCachePool;

    public function __construct(ManagerRegistry $registry, CacheItemPoolInterface $doctrinePhoneCachePool)
    {
        $this->doctrinePhoneCachePool = $doctrinePhoneCachePool;

        parent::__construct($registry, Phone::class);
    }

    public function phonePagination($request): PaginatedRepresentation
    {
        $routeName = $request->attributes->get('_route');
        $page = $request->query->get('page', 1);
        $limit = $request->query->get('limit', 10);

        $paginatedCollection =  $this->doctrinePhoneCachePool->get('phone_list page=' . $page . ',limit=' . $limit, function (ItemInterface $item) use ($page, $limit, $routeName) {

            $query =  $this->createQueryBuilder('p');
            $query =  $query->getQuery();

            $paginator =  $this->paginate($query, $page, $limit);

            return   $this->findPaginatedList($paginator, $routeName);
        });

        return $paginatedCollection;
    }

    private function findPaginatedList($paginator, $routeName): PaginatedRepresentation
    {
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

    // /**
    //  * @return Phone[] Returns an array of Phone objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Phone
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
