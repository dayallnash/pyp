<?php

namespace App\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Generator;

/**
 * Class BaseRepository.
 */
class BaseRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry, string $class = '')
    {
        parent::__construct($registry, $class);
    }

    public function findAllAsGenerator(string $orderBy = 'ASC'): Generator
    {
        foreach ($this->createQueryBuilder('t')->orderBy('t.id', $orderBy)->getQuery()->getResult() as $result) {
            yield $result;
        }
    }

    public function delete(int $primaryKeyId): bool
    {
        return (bool) $this->createQueryBuilder('t')
            ->delete()
            ->where('t.id = :primaryKeyId')
            ->setParameter('primaryKeyId', $primaryKeyId)
            ->getQuery()
            ->execute();
    }
}
