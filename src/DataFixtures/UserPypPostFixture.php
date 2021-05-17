<?php

namespace App\DataFixtures;

use App\Entity\Post;
use App\Entity\User;
use App\Entity\UserPypPost;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class UserPypPostFixture extends Fixture
{
    /**
     * @depends UserFixture
     * @depends PostFixture
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager): void
    {
        $userPypPost = (new UserPypPost())
            ->setUser($manager->getRepository(User::class)->find(1))
            ->setPost($manager->getRepository(Post::class)->find(1));

        $manager->persist($userPypPost);

        $userPypPost = (new UserPypPost())
            ->setUser($manager->getRepository(User::class)->find(1))
            ->setPost($manager->getRepository(Post::class)->find(2));

        $manager->persist($userPypPost);

        $userPypPost = (new UserPypPost())
            ->setUser($manager->getRepository(User::class)->find(2))
            ->setPost($manager->getRepository(Post::class)->find(3));

        $manager->persist($userPypPost);

        $manager->flush();
    }
}
