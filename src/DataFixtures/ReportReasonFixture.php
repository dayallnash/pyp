<?php

namespace App\DataFixtures;

use App\Entity\ReportReason;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ReportReasonFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $reportReason1 = new ReportReason();
        $reportReason1->setName('report_reason_1');
        $reportReason1->setParent(null);

        $manager->persist($reportReason1);

        $reportReason2 = new ReportReason();
        $reportReason2->setName('report_reason_2');
        $reportReason2->setParent(null);

        $manager->persist($reportReason2);

        $reportReason3 = new ReportReason();
        $reportReason3->setName('report_reason_3');
        $reportReason3->setParent($reportReason1);

        $manager->persist($reportReason3);

        $manager->flush();
    }
}
