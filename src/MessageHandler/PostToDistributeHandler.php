<?php

namespace App\MessageHandler;

use App\Entity\User;
use App\Entity\UserPypPost;
use App\Helper\PypHelper;
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
        $usersOrderedByPypCounts = $this->em->getRepository(UserPypPost::class)->getUsersOrderedByPypCounts();

        // Make sure user who posted always sees their own posts
        $userPypPost = (new UserPypPost())->setPost($post->getPost())->setUserId($post->getPost()->getUserId());
        $this->em->persist($userPypPost);

        $influence = $this->influenceCalculator->calculate($this->userRetriever->retrieve($post->getPost()->getUserId()));

        $i = 0;
        foreach ($usersOrderedByPypCounts as $userOrderedByPypCounts) {
            if ($post->getPost()->getUserId() === $userOrderedByPypCounts[0]->getId()) {
                continue;
            }

            if ($i >= $influence) {
                break;
            }

            $userPypPost = (new UserPypPost())->setPost($post->getPost())->setUserId($userOrderedByPypCounts[0]->getId());
            $this->em->persist($userPypPost);
        }

        try {
            $this->em->flush();
        } catch (Throwable $t) {
            error_log($t->getTraceAsString()."\n>\n>\n".$t->getMessage().' > '.$t->getFile().' > '.$t->getLine());
        }
    }
}
