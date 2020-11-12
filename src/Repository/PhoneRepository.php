<?php

namespace App\Repository;

use LogicException;
use App\Entity\Phone;
use Pagerfanta\Pagerfanta;
use Psr\Cache\CacheItemPoolInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Component\HttpFoundation\Request;
use Hateoas\Representation\PaginatedRepresentation;
use Hateoas\Representation\CollectionRepresentation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

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

    /**
     * @param ManagerRegistry $registry
     * @param CacheItemPoolInterface $doctrinePhoneCachePool
     * @return void
     * @throws LogicException
     */
    public function __construct(ManagerRegistry $registry, CacheItemPoolInterface $doctrinePhoneCachePool)
    {
        $this->doctrinePhoneCachePool = $doctrinePhoneCachePool;

        parent::__construct($registry, Phone::class);
    }

    /**
     * @param Request $request
     * @return PaginatedRepresentation
     */
    public function phonePagination(Request $request): PaginatedRepresentation
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

    /**
     * @param Pagerfanta $paginator
     * @param string $routeName
     * @return PaginatedRepresentation
     */
    private function findPaginatedList(PagerFanta $paginator, string $routeName): PaginatedRepresentation
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
