<?php

namespace App\DataFixtures;

use App\Entity\Post;
use App\Entity\User;
use App\Entity\UserPypPost;
use Doctrine\ORM\EntityManagerInterface;

class UserPypPostFixture
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @depends UserFixture
     * @depends PostFixture
     */
    public function load(): void
    {
        $user1 = $this->em->getRepository(User::class)->find(1);
        $user2 = $this->em->getRepository(User::class)->find(2);

        $post1 = $this->em->getRepository(Post::class)->find(1);
        $post2 = $this->em->getRepository(Post::class)->find(2);
        $post3 = $this->em->getRepository(Post::class)->find(3);

        $userPypPost1 = (new UserPypPost())
            ->setUser($user1)
            ->setPost($post1);

        $this->em->persist($userPypPost1);
        $this->em->persist($post1);

        $user1->addUserPypPost($userPypPost1);

        $userPypPost2 = (new UserPypPost())
            ->setUser($user1)
            ->setPost($post2);

        $this->em->persist($userPypPost2);
        $this->em->persist($post2);

        $user1->addUserPypPost($userPypPost2);
        $this->em->persist($user1);

        $userPypPost3 = (new UserPypPost())
            ->setUser($user2)
            ->setPost($post3);

        $this->em->persist($userPypPost3);
        $this->em->persist($post3);

        $user2->addUserPypPost($userPypPost3);
        $this->em->persist($user2);

        $this->em->flush();
        $this->em->beginTransaction();
    }
}
