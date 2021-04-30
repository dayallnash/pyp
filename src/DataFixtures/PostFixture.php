<?php

namespace App\DataFixtures;

use App\Entity\Post;
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
        $post = (new Post())
            ->setUserId(1)
            ->setPostContent('Test content 1')
            ->setDatetimePosted(new DateTime());

        $manager->persist($post);

        $post = (new Post())
            ->setUserId(1)
            ->setPostContent('Test content 2')
            ->setDatetimePosted(new DateTime());

        $manager->persist($post);

        $post = (new Post())
            ->setUserId(2)
            ->setPostContent('Test content 3')
            ->setDatetimePosted(new DateTime());

        $manager->persist($post);

        $manager->flush();
    }
}
