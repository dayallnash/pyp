<?php

namespace App\MessageHandler;

use App\Entity\Post;
use App\Entity\User;
use App\Entity\UserPypPost;
use App\Exceptions\PostDistributionException;
use App\Message\PostToDistribute;
use App\Service\InfluenceCalculator;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Throwable;

class PostToDistributeHandler implements MessageHandlerInterface
{
    private EntityManagerInterface $em;
    private InfluenceCalculator $influenceCalculator;

    public function __construct(EntityManagerInterface $em, InfluenceCalculator $influenceCalculator)
    {
        $this->em = $em;
        $this->influenceCalculator = $influenceCalculator;
    }

    public function __invoke(PostToDistribute $postToDistribute): void
    {
        $users = $this->em->getRepository(User::class)->findAll();

        // Sort users by amount of UserPypPosts they have viewable
        usort($users, static function ($userA, $userB) {
            $userA->getUserPypPosts()->count() <=> $userB->getUserPypPosts()->count();
        });

        $post = $this->em->getRepository(Post::class)->find($postToDistribute->getPost()->getId());

        if (null === $post) {
            throw new PostDistributionException('Could not find Post referenced in PostToDistribute. Perhaps it has been deleted.');
        }

        $poster = $post->getUser();

        $influence = $this->influenceCalculator->calculate($poster);

        $i = 0;
        foreach ($users as $user) {
            if ($post->getUser()->getId() === $user->getId()) {
                continue;
            }

            if ($i >= $influence) {
                break;
            }

            $userPypPost = (new UserPypPost())->setPost($post)->setUser($user);

            $this->em->persist($userPypPost);
        }

        try {
            $this->em->flush();
        } catch (Throwable $t) {
            throw new PostDistributionException(sprintf("%s %s %s > %s > %d", $t->getTraceAsString(), PHP_EOL, $t->getMessage(), $t->getFile(), $t->getLine()));
        }
    }
}
