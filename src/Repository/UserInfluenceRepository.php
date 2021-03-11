<?php

namespace App\Repository;

use App\Entity\UserInfluence;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method UserInfluence|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserInfluence|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserInfluence[]    findAll()
 * @method UserInfluence[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserInfluenceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserInfluence::class);
    }

    // /**
    //  * @return UserInfluence[] Returns an array of UserInfluence objects
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
    public function findOneBySomeField($value): ?UserInfluence
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
