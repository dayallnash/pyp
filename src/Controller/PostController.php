<?php

namespace App\Controller;

use App\Entity\Post;
use App\Repository\InteractionRepository;
use App\Service\PostService;
use App\Service\UserRetriever;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PostController extends AbstractController
{
    /**
     * @Route("/post", name="app_post")
     *
     * @param Request     $request
     * @param PostService $postService
     *
     * @return RedirectResponse
     */
    public function post(Request $request, PostService $postService): RedirectResponse
    {
        $postService->savePost($this->getUser(), $request->request->filter('post', '', FILTER_SANITIZE_STRING));

        return $this->redirectToRoute('app_index');
    }

    /**
     * @Route("/render_individual_post", name="app_render_individual_post")
     *
     * @param Post                  $post
     * @param UserRetriever         $userRetriever
     * @param InteractionRepository $interactionRepo
     *
     * @return Response
     */
    public function renderPost(Post $post, UserRetriever $userRetriever, InteractionRepository $interactionRepo): Response
    {
        $commentCount = $likeCount = 0;
        $liked = false;
        foreach ($interactionRepo->findBy(['post' => $post]) as $interaction) {
            if ('comment' === $interaction->getType()) {
                ++$commentCount;
            } else {
                if ($interaction->getUserId() === $this->getUser()->getId()) {
                    $liked = true;
                }
                ++$likeCount;
            }
        }

        return $this->render('pipe/_post.html.twig', [
            'post' => $post,
            'user' => $userRetriever->retrieve($post->getUserId()),
            'commentCount' => $commentCount,
            'likeCount' => $likeCount,
            'liked' => $liked,
        ]);
    }
}