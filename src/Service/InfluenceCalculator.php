<?php

namespace App\Service;

use App\Entity\User;
use App\Entity\UserInfluence;
use Doctrine\Common\Collections\Criteria;
use Doctrine\Common\Collections\Expr\Comparison;
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
        $userInfluence = (new UserInfluence())->setUser($user)->setInfluence(self::DEFAULT_INFLUENCE);

        $this->em->persist($userInfluence);
        $this->em->flush();
    }

    public function calculate(User $user, ?EntityManagerInterface $em = null): int
    {
        $userInfluence = (new UserInfluence())->setUser($user)->setInfluence(self::DEFAULT_INFLUENCE);

        $userInfluence = $this->calculateInfluenceBasedOnInteractions($userInfluence, $em ?? $this->em);

        return $userInfluence->getInfluence();
    }

    private function calculateInfluenceBasedOnInteractions(UserInfluence $userInfluence, EntityManagerInterface $em): UserInfluence
    {
        $userInfluence = $this->calculateInfluenceBasedOnLikes($userInfluence);

        $userInfluence = $this->calculateInfluenceBasedOnComments($userInfluence);

        if ($userInfluence->getInfluence() < self::DEFAULT_INFLUENCE) {
            $userInfluence->setInfluence(self::DEFAULT_INFLUENCE);
        }

        $em->persist($userInfluence);

        return $userInfluence;
    }

    private function calculateInfluenceBasedOnLikes(UserInfluence $userInfluence): UserInfluence
    {
        $criteria = new Criteria();

        $count = 0;
        foreach ($userInfluence->getUser()->getPosts() as $post) {
            $count += $post->getInteractions()->matching(
                $criteria->where(new Comparison('type', '=', 'like'))
            )->count();
        }

        $userInfluence->setInfluence($count * 10);

        return $userInfluence;
    }

    private function calculateInfluenceBasedOnComments(UserInfluence $userInfluence): UserInfluence
    {
        $criteria = new Criteria();

        $influenceRunningTotal = 0;
        foreach ($userInfluence->getUser()->getPosts() as $post) {
            $comments = $post->getInteractions()->matching(
                $criteria->where(new Comparison('type', '=', 'comment'))
            );

            $uniqueUsers = [];
            $uniqueCommentsCount = 0;
            $secondaryCommentsCount = 0;
            foreach ($comments as $comment) {
                if (in_array($comment->getUser(), $uniqueUsers, true)) {
                    ++$secondaryCommentsCount;
                } else {
                    $uniqueUsers[] = $comment->getUser();
                    ++$uniqueCommentsCount;
                }
            }

            $influenceRunningTotal += ($secondaryCommentsCount * 10) + ($uniqueCommentsCount * 50);
        }

        $userInfluence->setInfluence($influenceRunningTotal);

        return $userInfluence;
    }
}
