<?php

namespace App\Controller;

use App\Entity\Interaction;
use App\Entity\Post;
use App\Repository\InteractionRepository;
use App\Repository\PostRepository;
use App\Service\UserRetriever;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class InteractionController extends AbstractController
{
    /**
     * @Route("/interaction/comment", name="render_comment")
     *
     * @param Post                  $post
     * @param InteractionRepository $interactionRepo
     * @param UserRetriever         $userRetriever
     *
     * @return Response
     */
    public function renderComment(Post $post, InteractionRepository $interactionRepo, UserRetriever $userRetriever): Response
    {
        return $this->render('interaction/_comment.html.twig', [
            'userRetriever' => $userRetriever,
            'interactions' => $interactionRepo->findBy(['post' => $post]),
            'post' => $post,
        ]);
    }

    /**
     * @Route("/interaction/comment/save", name="save_comment")
     *
     * @param Request                $request
     * @param PostRepository         $postRepo
     * @param EntityManagerInterface $em
     *
     * @return Response
     */
    public function saveComment(Request $request, PostRepository $postRepo, EntityManagerInterface $em): Response
    {
        $interaction = (new Interaction())
            ->setPost($postRepo->find($request->query->filter('post', 0, FILTER_SANITIZE_NUMBER_INT)))
            ->setBody($request->request->filter('comment', '', FILTER_SANITIZE_STRING))
            ->setUserId($this->getUser()->getId())
            ->setType('comment');

        $em->persist($interaction);
        $em->flush();

        return $this->redirectToRoute('app_index');
    }

    /**
     * @Route("/interaction/like/save", name="like_post")
     *
     * @param Request                $request
     * @param PostRepository         $postRepo
     * @param InteractionRepository  $interactionRepo
     * @param EntityManagerInterface $em
     *
     * @return JsonResponse
     */
    public function likePost(Request $request, PostRepository $postRepo, InteractionRepository $interactionRepo, EntityManagerInterface $em): JsonResponse
    {
        $post = $postRepo->find($request->request->filter('postId', 0, FILTER_SANITIZE_NUMBER_INT));

        $interaction = $interactionRepo->findOneBy(['type' => 'like', 'post' => $post, 'userId' => $this->getUser()->getId()]);

        if (null !== $interaction) {
            $em->remove($interaction);
            $em->flush();

            return $this->json(['success' => true]);
        }

        $interaction = (new Interaction())
            ->setPost($post)
            ->setUserId($this->getUser()->getId())
            ->setType('like');

        $em->persist($interaction);
        $em->flush();

        return $this->json(['success' => true]);
    }
}
