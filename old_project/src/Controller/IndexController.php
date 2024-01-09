<?php

namespace App\Controller;

use App\Entity\UserPypPost;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\Common\Collections\Expr\Comparison;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class IndexController extends AbstractController
{
    /**
     * @Route("/", name="app_index")
     *
     * @param EntityManagerInterface $em
     *
     * @return Response
     */
    public function index(EntityManagerInterface $em): Response
    {
        $currentUser = $this->getUser();

        if (null === $currentUser) {
            return $this->redirectToRoute('app_home');
        }

        $userPypPosts = $em->getRepository(UserPypPost::class)->findBy(['user' => $currentUser], ['post' => 'DESC']);

        foreach ($userPypPosts as $postKey => $userPypPost) {
            $reportedComments = [];
            $commentCount = $likeCount = 0;
            $liked = false;

            $interactions = $this->getInteractionsData($userPypPost, $currentUser, $commentCount, $likeCount, $reportedComments, $liked);

            $userPypPosts[$postKey] = [
                'post' => $userPypPost->getPost(),
                'user' => $userPypPost->getPost()->getUser(),
                'commentCount' => $commentCount,
                'likeCount' => $likeCount,
                'liked' => $liked,
                'currentUser' => $currentUser,
                'interactions' => $interactions,
                'reportedComments' => $reportedComments,
            ];
        }

        return $this->render('index/index.html.twig', [
            'posts' => $userPypPosts,
        ]);
    }

    /**
     * @Route("/privacy", name="app_privacy_policy")
     */
    public function privacyPolicy(): Response
    {
        return $this->render('index/privacy.html.twig');
    }

    /**
     * @Route("/sw.js", name="sw_js_route")
     */
    public function swJsRoute(): Response
    {
        return new Response();
    }

    private function getInteractionsData(
        UserPypPost $userPypPost,
        UserInterface $currentUser,
        int &$commentCount,
        int &$likeCount,
        array &$reportedComments,
        bool &$liked
    ): Collection {
        $interactions = $userPypPost->getPost()->getInteractions();

        foreach ($interactions as $interaction) {
            if ('comment' === $interaction->getType()) {
                ++$commentCount;

                if ($interaction->getReports()->matching(
                    (new Criteria())->where(
                        new Comparison('user', '=', $currentUser)
                    ))
                ) {
                    $reportedComments[] = $interaction;
                }
            } else {
                if ($interaction->getUser() === $currentUser) {
                    $liked = true;
                }
                ++$likeCount;
            }
        }

        return $interactions;
    }
}
