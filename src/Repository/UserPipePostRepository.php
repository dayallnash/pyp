<?php

namespace App\Repository;

use App\Entity\User;
use App\Entity\UserPipePost;
use Doctrine\ORM\Query\Expr;
use Doctrine\Persistence\ManagerRegistry;
use Generator;

/**
 * @method UserPipePost|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserPipePost|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserPipePost[]    findAll()
 * @method UserPipePost[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserPipePostRepository extends BaseRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserPipePost::class);
    }

    public function getUsersOrderedByPipeCounts(string $orderBy = 'ASC'): array
    {
        $qb = $this->getEntityManager()->createQueryBuilder()
            ->select('COUNT(upp.id) upp_count, u')
            ->from($this->_entityName, 'upp')
            ->innerJoin(User::class, 'u', Expr\Join::WITH, 'u.id = upp.userId')
            ->groupBy('upp.userId')
            ->orderBy('upp_count', $orderBy);

        $query = $qb->getQuery();

        return $query->execute();
    }

    public function getUserPipePostsCount(int $userId): int
    {
        $qb = $this->getEntityManager()->createQueryBuilder();

        return $qb->select('COUNT(upp.id) upp_count')
            ->from($this->_entityName, 'upp')
            ->where($qb->expr()->eq('upp.userId', ':userId'))
            ->setParameter('userId', $userId)
            ->getQuery()
            ->execute()[0]['upp_count'];
    }

    public function getAllPostsForUser(User $user): Generator
    {
        $qb = $this->getEntityManager()->createQueryBuilder();

        $results = $qb
            ->select('upp')
            ->from(UserPipePost::class, 'upp')
            ->where($qb->expr()->eq('upp.userId', ':userId'))
            ->setParameter('userId', $user->getId())
            ->getQuery();

        foreach ($results->getResult() as $result) {
            yield $result;
        }
    }
}
