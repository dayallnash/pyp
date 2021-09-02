<?php

namespace App\DataFixtures;

use Liip\FunctionalTestBundle\Test\WebTestCase;
use Psr\Container\ContainerInterface;
use Symfony\Component\HttpKernel\KernelInterface;

class FixtureServiceLoader extends WebTestCase
{
    public static function bootFixtureContainer(): ContainerInterface
    {
        parent::bootKernel();

        return (new FixtureServiceLoader())->getContainer();
    }
}
