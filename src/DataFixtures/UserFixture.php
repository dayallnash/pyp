<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoder;

class UserFixture extends Fixture
{
    public function load($manager): void
    {
        $fixtureServiceLoader = new FixtureServiceLoader();

        $kernel = $fixtureServiceLoader::bootFixtureKernel();

        $userPasswordEncoder = $kernel->getContainer()->get(UserPasswordEncoder::class);

        if (null === $userPasswordEncoder) {
            return;
        }

        $user1 = new User();
        $user1->setUsername('test_user_1');
        $encodedPassword = $userPasswordEncoder->encodePassword($user1, 'test_user_1');
        $user1->setPassword($encodedPassword);
        $user1->setHoseUser('n');
        $manager->persist($user1);

        $user2 = new User();
        $user2->setUsername('test_user_2');
        $encodedPassword = $userPasswordEncoder->encodePassword($user2, 'test_user_2');
        $user2->setPassword($encodedPassword);
        $user2->setHoseUser('n');
        $manager->persist($user2);

        $manager->flush();
        $manager->getConnection()->beginTransaction();
    }
}
