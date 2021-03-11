<?php

namespace App\Service;

use App\Entity\User;
use App\Entity\UserInfluence;
use Doctrine\ORM\EntityManagerInterface;

class InfluenceCalculator
{
    private EntityManagerInterface $em;

    private const DEFAULT_INFLUENCE = 500;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function initialiseInfluence(User $user): void
    {
        $userInfluence =(new UserInfluence())->setUserId($user->getId())->setInfluence(self::DEFAULT_INFLUENCE);

        $this->em->persist($userInfluence);
        $this->em->flush();
    }

    public function calculate(User $user): int
    {
        // TODO actual calculation

        return self::DEFAULT_INFLUENCE / 10;
    }
}