<?php

namespace App\Service;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class UserRetriever
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function retrieve(int $userId): ?User
    {
        return $this->em->getRepository(User::class)->find($userId);
    }
}