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

    public function getAllPostsForUser(User $user): Generator
    {
        $qb = $this->getEntityManager()->createQueryBuilder();

        $results = $qb
            ->select('upp')
            ->from(UserPypPost::class, 'upp')
            ->where($qb->expr()->eq('upp.user', ':user'))
            ->setParameter('user', $user)
            ->getQuery();

        foreach ($results->getResult() as $result) {
            yield $result;
        }
    }
}
