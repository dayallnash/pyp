<?php

namespace App\DataFixtures;

use Liip\FunctionalTestBundle\Test\WebTestCase;
use Symfony\Component\HttpKernel\KernelInterface;

class FixtureServiceLoader extends WebTestCase
{
    public static function bootFixtureKernel(): KernelInterface
    {
        return parent::bootKernel();
    }
}
