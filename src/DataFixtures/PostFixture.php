<?php

namespace App\DataFixtures;

use App\Entity\Post;
use App\Entity\User;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class PostFixture extends Fixture
{
    /**
     * @depends UserFixture
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager): void
    {
        $user1 = $manager->getRepository(User::class)->find(1);
        $user2 = $manager->getRepository(User::class)->find(2);

        $post1 = (new Post())
            ->setUser($user1)
            ->setPostContent('Test content 1')
            ->setDatetimePosted(new DateTime());

        $manager->persist($post1);
        $user1->addPost($post1);

        $post2 = (new Post())
            ->setUser($user1)
            ->setPostContent('Test content 2')
            ->setDatetimePosted(new DateTime());

        $manager->persist($post2);

        $user1->addPost($post2);
        $manager->persist($user1);

        $post3 = (new Post())
            ->setUser($user2)
            ->setPostContent('Test content 3')
            ->setDatetimePosted(new DateTime());

        $manager->persist($post3);

        $user2->addPost($post3);
        $manager->persist($user2);

        $manager->flush();
    }
}
