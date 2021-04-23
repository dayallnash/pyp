<?php

namespace App\Tests;

use Doctrine\Common\DataFixtures\Executor\AbstractExecutor;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Liip\FunctionalTestBundle\Test\WebTestCase;
use Liip\TestFixturesBundle\Test\FixturesTrait;

class Base extends WebTestCase
{
    use FixturesTrait;

    public function getEntityManager()
    {
        self::bootKernel();

        return self::$kernel->getContainer()->get('doctrine')->getManager();
    }

    public function purgeDatabase(): void
    {
        $purger = new ORMPurger($this->getEntityManager());
        $purger->purge();
    }

    public function loadFixturesParent(
        array $classNames = [],
        bool $append = false,
        ?string $omName = null,
        string $registryName = 'doctrine',
        ?int $purgeMode = null
    ): ?AbstractExecutor {
        $this->purgeDatabase();

        return $this->loadFixtures($classNames, $append, $omName, $registryName, $purgeMode);
    }
}
