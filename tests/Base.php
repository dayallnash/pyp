<?php

namespace App\Tests;

use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\ORM\EntityManagerInterface;
use Liip\FunctionalTestBundle\Test\WebTestCase;
use RuntimeException;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\StringInput;
use Symfony\Component\Console\Output\BufferedOutput;

abstract class Base extends WebTestCase
{
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
        array $classNames = []
    ): void {
        if (null === self::$kernel) {
            self::bootKernel();
        }

        if ('test' !== strtolower(self::$kernel->getContainer()->getParameter('app.env'))) {
            throw new RuntimeException('Error! You are not on the test environment. If you are seeing this from your dev machine, modify your .env.local APP_ENV variable to point at "test"');
        }

        $application = new Application(self::$kernel);
        $application->setAutoExit(false);

        $input = new StringInput('doctrine:database:drop --force');

        $application->run($input);

        $input = new StringInput('doctrine:database:create');

        $application->run($input);

        $input = new StringInput('doctrine:migrations:migrate --no-interaction');

        $application->run($input, new BufferedOutput());

        foreach ($classNames as $className) {
            $class = self::$kernel->getContainer()->get($className);

            $class->load($this->getEntityManager());
        }
    }
}
