<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }
    
    public function findBestUser($limit){
        return $this->createQueryBuilder('u')
                    ->select('u as user, AVG(c.note) as avgNote, COUNT(c) as sumRatings')
                    ->join('u.ratings', 'c')
                    ->groupBy('u')
                    ->having('sumRatings > 2')
                    ->orderBy('avgNote', 'DESC')
                    ->setMaxResults($limit)
                    ->getQuery()->getResult();
    }

    public function search($city, $job){
        return $this->createQueryBuilder('s')
                    ->andWhere('s.city = :city')
                    ->setParameter('city', $city)
                    ->andWhere('s.job = :job')
                    ->setParameter('job', $job)
                    ->getQuery()->getResult();
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
