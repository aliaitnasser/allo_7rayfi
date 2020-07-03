<?php

namespace App\Repository;

use App\Entity\SearchUser;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method SearchUser|null find($id, $lockMode = null, $lockVersion = null)
 * @method SearchUser|null findOneBy(array $criteria, array $orderBy = null)
 * @method SearchUser[]    findAll()
 * @method SearchUser[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SearchUserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SearchUser::class);
    }

    // /**
    //  * @return SearchUser[] Returns an array of SearchUser objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?SearchUser
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
