<?php

namespace App\MessageHandler;

use App\Entity\Post;
use App\Entity\User;
use App\Entity\UserPypPost;
use App\Message\PostToDistribute;
use App\Service\InfluenceCalculator;
use Doctrine\ORM\EntityManagerInterface;
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

    public function __invoke(PostToDistribute $post): void
    {
        $users = $this->em->getRepository(User::class)->findAll();

        // Sort users by amount of UserPypPosts they have viewable
        usort($users, static function ($userA, $userB) {
            $userA->getUserPypPosts()->count() <=> $userB->getUserPypPosts()->count();
        });

        $post = $this->em->getRepository(Post::class)->find($post->getPost()->getId());

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
            error_log($t->getTraceAsString()."\n>\n>\n".$t->getMessage().' > '.$t->getFile().' > '.$t->getLine());
        }
    }
}
