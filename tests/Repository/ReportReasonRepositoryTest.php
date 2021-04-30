<?php

namespace App\Tests\Repository;

use App\DataFixtures\ReportReasonFixture;
use App\Entity\ReportReason;
use App\Tests\Base;

class ReportReasonRepositoryTest extends Base
{
    public function testGetTopLevelReportReasons(): void
    {
        $this->loadFixturesParent([
            ReportReasonFixture::class,
        ]);

        self::assertCount(
            2,
            $this->getEntityManager()->getRepository(ReportReason::class)->getTopLevelReportReasons()
        );
    }
}
