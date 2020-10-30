<?php

namespace App\Repository;

use App\Entity\User;
use Pagerfanta\Pagerfanta;
use App\Representation\UsersRepresentation;
use Psr\Cache\CacheItemPoolInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Component\HttpFoundation\Request;
use Hateoas\Representation\CollectionRepresentation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use LogicException;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    use AbstractRepository;

    private $doctrineUserCachePool;

    /**
     * @param ManagerRegistry $registry 
     * @param CacheItemPoolInterface $doctrineUserCachePool 
     * @return void 
     * @throws LogicException 
     */
    public function __construct(ManagerRegistry $registry, CacheItemPoolInterface $doctrineUserCachePool)
    {
        $this->doctrineUserCachePool = $doctrineUserCachePool;

        parent::__construct($registry, User::class);
    }

    /**
     * @param Request $request 
     * @param int $companyId 
     * @return UsersRepresentation 
     */
    public function usersPagination(Request $request, int $companyId): UsersRepresentation
    {
        $routeName = $request->attributes->get('_route');
        $page = $request->query->get('page', 1);
        $limit = $request->query->get('limit', 10);

        $paginatedCollection =  $this->doctrineUserCachePool->get('user_list companyId=' . $companyId . 'page=' . $page . ',limit=' . $limit, function (ItemInterface $item) use ($page, $limit, $routeName, $companyId) {

            $query = $this->createQueryBuilder('u')
                ->where('u.company = :companyId')
                ->setParameters([
                    'companyId' => $companyId,
                ]);
            $query =  $query->getQuery();

            $paginator =  $this->paginate($query, $page, $limit);

            return   $this->findPaginatedList($paginator, $routeName);
        });

        return $paginatedCollection;
    }

    /**
     * @param Pagerfanta $paginator 
     * @param string $routeName 
     * @return UsersRepresentation 
     */
    private function findPaginatedList(Pagerfanta $paginator, string $routeName): UsersRepresentation
    {
        $paginatedCollection =  new UsersRepresentation(
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
    //  * @return User[] Returns an array of User objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?User
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
