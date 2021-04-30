<?php

namespace App\Tests;

use Doctrine\Common\DataFixtures\Executor\AbstractExecutor;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\ORM\EntityManagerInterface;
use Liip\FunctionalTestBundle\Test\WebTestCase;
use Liip\TestFixturesBundle\Test\FixturesTrait;
use RuntimeException;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\StringInput;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\Console\Output\ConsoleOutput;

abstract class Base extends WebTestCase
{
    use FixturesTrait;

    public function getEntityManager(): EntityManagerInterface
    {
        if (null === self::$kernel) {
            self::bootKernel();
        }

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
        if (null === self::$kernel) {
            self::bootKernel();
        }
        
        if ('test' !== strtolower(self::$kernel->getContainer()->getParameter('app.env'))) {
            throw new RuntimeException('Error! You are not on the test environment. If you are seeing this from your dev machine, modify your .env.local APP_ENV variable to point at "test"');
        }

        $application = new Application(self::$kernel);
        $application->setAutoExit(false);

        $input = new ArrayInput([
            'command' => 'doctrine:schema:drop',
            '--force' => true,
        ]);

        $application->run($input);

        $input = new ArrayInput([
            'command' => 'doctrine:schema:create',
        ]);

        $application->run($input);

        $input = new ArrayInput(array(
            'command' => 'doctrine:migrations:migrate',
            '--no-interaction' => true,
        ));

        $application->run($input, new BufferedOutput());

        return $this->loadFixtures($classNames, $append, $omName, $registryName, $purgeMode);
    }
}
