<?php

namespace App\Repository;

use App\Entity\User;
use App\Entity\UserPypPost;
use App\Service\UserRetriever;
use Doctrine\ORM\Query\Expr;
use Doctrine\Persistence\ManagerRegistry;
use Generator;

/**
 * @method UserPypPost|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserPypPost|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserPypPost[]    findAll()
 * @method UserPypPost[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserPypPostRepository extends BaseRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserPypPost::class);
    }

    public function getUsersOrderedByPypCounts(string $orderBy = 'ASC'): array
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

    public function getAllPostsForUser(User $user): Generator
    {
        $qb = $this->getEntityManager()->createQueryBuilder();

        $results = $qb
            ->select('upp')
            ->from(UserPypPost::class, 'upp')
            ->where($qb->expr()->eq('upp.userId', ':userId'))
            ->setParameter('userId', $user->getId())
            ->getQuery();

        foreach ($results->getResult() as $result) {
            yield $result;
        }
    }
}
