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
     */
    public function load($manager): void
    {
        $user1 = $manager->getRepository(User::class)->find(1);
        $user2 = $manager->getRepository(User::class)->find(2);

        $post1 = $manager->getRepository(Post::class)->find(1);
        $post2 = $manager->getRepository(Post::class)->find(2);
        $post3 = $manager->getRepository(Post::class)->find(3);

        $userPypPost1 = (new UserPypPost())
            ->setUser($user1)
            ->setPost($post1);

        $manager->persist($userPypPost1);
        $manager->persist($post1);

        $user1->addUserPypPost($userPypPost1);

        $userPypPost2 = (new UserPypPost())
            ->setUser($user1)
            ->setPost($post2);

        $manager->persist($userPypPost2);
        $manager->persist($post2);

        $user1->addUserPypPost($userPypPost2);
        $manager->persist($user1);

        $userPypPost3 = (new UserPypPost())
            ->setUser($user2)
            ->setPost($post3);

        $manager->persist($userPypPost3);
        $manager->persist($post3);

        $user2->addUserPypPost($userPypPost3);
        $manager->persist($user2);

        $manager->flush();
    }
}
