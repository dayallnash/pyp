<?php

namespace App\MessageHandler;

use App\Entity\User;
use App\Entity\UserPipePost;
use App\Helper\PipeHelper;
use App\Message\PostToDistribute;
use App\Service\InfluenceCalculator;
use App\Service\UserRetriever;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Throwable;

class PostToDistributeHandler implements MessageHandlerInterface
{
    private EntityManagerInterface $em;
    private InfluenceCalculator $influenceCalculator;
    private UserRetriever $userRetriever;

    public function __construct(EntityManagerInterface $em, InfluenceCalculator $influenceCalculator, UserRetriever $userRetriever)
    {
        $this->em = $em;
        $this->influenceCalculator = $influenceCalculator;
        $this->userRetriever = $userRetriever;
    }

    public function __invoke(PostToDistribute $post)
    {
        $usersOrderedByPipeCounts = $this->em->getRepository(UserPipePost::class)->getUsersOrderedByPipeCounts();

        // Make sure user who posted always sees their own posts
        $userPipePost = (new UserPipePost())->setPost($post->getPost())->setUserId($post->getPost()->getUserId());
        $this->em->persist($userPipePost);

        $influence = $this->influenceCalculator->calculate($this->userRetriever->retrieve($post->getPost()->getUserId()));

        $i = 0;
        foreach ($usersOrderedByPipeCounts as $userOrderedByPipeCounts) {
            if ($post->getPost()->getUserId() === $userOrderedByPipeCounts[0]->getId()) {
                continue;
            }

            if ($i >= $influence) {
                break;
            }

            $userPipePost = (new UserPipePost())->setPost($post->getPost())->setUserId($userOrderedByPipeCounts[0]->getId());
            $this->em->persist($userPipePost);
        }

        try {
            $this->em->flush();
        } catch (Throwable $t) {
            error_log($t->getTraceAsString()."\n>\n>\n".$t->getMessage().' > '.$t->getFile().' > '.$t->getLine());
        }
    }
}
