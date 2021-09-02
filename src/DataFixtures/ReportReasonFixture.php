<?php

namespace App\DataFixtures;

use App\Entity\ReportReason;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;

class ReportReasonFixture
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function load(): void
    {
        $reportReason1 = new ReportReason();
        $reportReason1->setName('report_reason_1');
        $reportReason1->setParent(null);

        $this->em->persist($reportReason1);

        $reportReason2 = new ReportReason();
        $reportReason2->setName('report_reason_2');
        $reportReason2->setParent(null);

        $this->em->persist($reportReason2);

        $reportReason3 = new ReportReason();
        $reportReason3->setName('report_reason_3');
        $reportReason3->setParent($reportReason1);

        $this->em->persist($reportReason3);

        $this->em->flush();
        $this->em->beginTransaction();
    }
}
