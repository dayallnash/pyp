<?php

namespace App\DataFixtures;

use App\Entity\Post;
use App\Entity\User;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;

class PostFixture
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @depends UserFixture
     */
    public function load(): void
    {
        $user1 = $this->em->getRepository(User::class)->find(1);
        $user2 = $this->em->getRepository(User::class)->find(2);

        $post1 = (new Post())
            ->setUser($user1)
            ->setPostContent('Test content 1')
            ->setDatetimePosted(new DateTime());

        $this->em->persist($post1);
        $user1->addPost($post1);

        $post2 = (new Post())
            ->setUser($user1)
            ->setPostContent('Test content 2')
            ->setDatetimePosted(new DateTime());

        $this->em->persist($post2);

        $user1->addPost($post2);
        $this->em->persist($user1);

        $post3 = (new Post())
            ->setUser($user2)
            ->setPostContent('Test content 3')
            ->setDatetimePosted(new DateTime());

        $this->em->persist($post3);

        $user2->addPost($post3);
        $this->em->persist($user2);

        $this->em->flush();
        $this->em->beginTransaction();
    }
}
