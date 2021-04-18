<?php

namespace App\Repository;

use App\Entity\ReportReason;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ReportReason|null find($id, $lockMode = null, $lockVersion = null)
 * @method ReportReason|null findOneBy(array $criteria, array $orderBy = null)
 * @method ReportReason[]    findAll()
 * @method ReportReason[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReportReasonRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ReportReason::class);
    }

    public function getTopLevelReportReasons()
    {
        $qb = $this->getEntityManager()->createQueryBuilder();

        return $qb->select('rr')
            ->from($this->_entityName, 'rr')
            ->where($qb->expr()->isNull('rr.parent'))
            ->getQuery()
            ->execute();
    }
}
