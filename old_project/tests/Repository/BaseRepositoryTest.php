<?php

namespace App\Tests\Repository;

use App\DataFixtures\UserFixture;
use App\Entity\User;
use App\Tests\Base;
use Generator;

class BaseRepositoryTest extends Base
{
    public function testFindAllAsGenerator(): void
    {
        $this->loadFixturesParent([
            UserFixture::class,
        ]);

        $userRepo = $this->getEntityManager()->getRepository(User::class);

        $generator = $userRepo->findAllAsGenerator();

        self::assertInstanceOf(Generator::class, $generator);
    }

    public function testDelete(): void
    {
        $this->loadFixturesParent([
            UserFixture::class,
        ]);

        $userRepo = $this->getEntityManager()->getRepository(User::class);

        $users = $userRepo->findAll();

        foreach ($users as $user) {
            $userRepo->delete($user->getId());
        }

        self::assertCount(0, $userRepo->findAll());
    }
}
