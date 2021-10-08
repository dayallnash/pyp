<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixture
{
    private UserPasswordHasherInterface $hasher;
    private EntityManagerInterface $em;

    public function __construct(UserPasswordHasherInterface $hasher, EntityManagerInterface $em)
    {
        $this->hasher = $hasher;
        $this->em = $em;
    }

    public function load(): void
    {
        if (null === $this->hasher) {
            return;
        }

        $user1 = new User();
        $user1->setUsername('test_user_1');
        $hashedPassword = $this->hasher->hashPassword($user1, 'test_user_1');
        $user1->setPassword($hashedPassword);
        $user1->setHoseUser('n');
        $this->em->persist($user1);

        $user2 = new User();
        $user2->setUsername('test_user_2');
        $hashedPassword = $this->hasher->hashPassword($user2, 'test_user_2');
        $user2->setPassword($hashedPassword);
        $user2->setHoseUser('n');
        $this->em->persist($user2);

        $this->em->flush();
        $this->em->getConnection()->beginTransaction();
    }
}
