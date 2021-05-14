<?php

namespace App\Service;

use App\Entity\Post;
use App\Entity\User;
use App\Entity\UserInfluence;
use App\Entity\UserPypPost;
use App\Message\PostToDistribute;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class PostService
{
    private EntityManagerInterface $em;
    private MessageBusInterface $bus;
    private InfluenceCalculator $influenceCalculator;

    public function __construct(EntityManagerInterface $em, MessageBusInterface $bus, InfluenceCalculator $influenceCalculator)
    {
        $this->em = $em;
        $this->bus = $bus;
        $this->influenceCalculator = $influenceCalculator;
    }

    public function savePost(User $user, string $content): void
    {
        if (null === $this->em->getRepository(UserInfluence::class)->findOneBy(['userId' => $user->getId()])) {
            $this->influenceCalculator->initialiseInfluence($user);
        }

        $post = (new Post())
            ->setUser($user)
            ->setDatetimePosted(new DateTime())
            ->setPostContent($content);

        // Make sure user who posted always sees their own posts
        $userPypPost = (new UserPypPost())
            ->setUser($user)
            ->setPost($post);

        $this->em->persist($post);
        $this->em->persist($userPypPost);
        $this->em->flush();

        if (null !== $this->bus) {
            $this->distributePost($post);
        }
    }

    public function distributePost(Post $post): void
    {
        $this->bus->dispatch((new PostToDistribute())->setPost($post));
    }
}