<?php

namespace App\Controller;

use App\Entity\Post;
use App\Helper\PostHelper;
use App\Message\PostToDistribute;
use App\Service\PostService;
use App\Service\UserRetriever;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

class PostController extends AbstractController
{
    /**
     * @Route("/post", name="app_post")
     *
     * @param Request                $request
     * @param EntityManagerInterface $em
     * @param MessageBusInterface    $bus
     * @param PostService            $postService
     *
     * @return RedirectResponse
     */
    public function post(Request $request, EntityManagerInterface $em, MessageBusInterface $bus, PostService $postService)
    {
        $postService->savePost($this->getUser(), $request->request->filter('post', '', FILTER_SANITIZE_STRING));

        return $this->redirectToRoute('app_index');
    }

    /**
     * @Route("/render_individual_post", name="app_render_individual_post")
     *
     * @param Post          $post
     * @param UserRetriever $userRetriever
     *
     * @return Response
     */
    public function renderPost(Post $post, UserRetriever $userRetriever)
    {
        return $this->render('pipe/_post.html.twig', ['post' => $post, 'user' => $userRetriever->retrieve($post->getUserId())]);
    }
}