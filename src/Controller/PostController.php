<?php

namespace App\Controller;

use App\Entity\Post;
use App\Entity\Report;
use App\Entity\ReportReason;
use App\Repository\UserPypPostRepository;
use App\Service\PostService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class PostController.
 */
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
     * @Route("/post/edit/{postId}", requirements={"postId"="\d+"}, name="edit_post")
     *
     * @param Request                $request
     * @param EntityManagerInterface $em
     * @param int                    $postId
     *
     * @return JsonResponse
     */
    public function editPost(Request $request, EntityManagerInterface $em, int $postId = 0): Response
    {
        $post = $em->getRepository(Post::class)->find(filter_var($postId, FILTER_SANITIZE_NUMBER_INT));

        $postContent = $request->request->filter('postContent', '', FILTER_SANITIZE_STRING);

        if (null !== $post && empty($postContent)) {
            return $this->render('post/edit.html.twig', [
                'post' => $post,
                'user' => $this->getUser(),
            ]);
        }

        if (null !== $post) {
            $post->setPostContent($postContent);

            $em->persist($post);
            $em->flush();
        }

        return $this->redirectToRoute('app_index');
    }

    /**
     * @Route("/post/delete/{postId}", requirements={"postId"="\d+"}, name="delete_post")
     *
     * @param int                    $postId
     * @param EntityManagerInterface $em
     *
     * @return Response
     */
    public function deletePost(EntityManagerInterface $em, int $postId = 0): Response
    {
        $postId = filter_var($postId, FILTER_SANITIZE_NUMBER_INT);

        if (empty($postId)) {
            return $this->redirectToRoute('app_index');
        }

        $post = $em->getRepository(Post::class)->find($postId);

        $interactions = [];
        $userPypPosts = [];
        if (null !== $post) {
            $interactions = $post->getInteractions();
            $userPypPosts = $post->getUserPypPosts();
        }

        foreach ($interactions as $interaction) {
            $em->remove($interaction);
        }

        foreach ($userPypPosts as $userPypPost) {
            $em->remove($userPypPost);
        }

        $em->flush();

        $this->addFlash('success', 'Successfully deleted post.');

        return $this->redirectToRoute('app_index');
    }

    /**
     * @Route("/post/report/{postId}", requirements={"postId"="\d+"}, name="report_post")
     *
     * @param Request                $request
     * @param EntityManagerInterface $em
     * @param UserPypPostRepository  $userPypPostRepository
     * @param int                    $postId
     *
     * @return Response
     */
    public function reportPost(
        Request $request,
        EntityManagerInterface $em,
        UserPypPostRepository $userPypPostRepository,
        int $postId = 0
    ): Response {
        $post = $em->getRepository(Post::class)->find($postId);

        if (null === $post) {
            $this->addFlash('danger', 'Could not find post to report.');

            return $this->redirectToRoute('app_index');
        }

        $reasonId = $request->request->filter('reportReasonParentId', 0, FILTER_SANITIZE_NUMBER_INT);

        if (!empty($reasonId)) {
            $reason = $em->getRepository(ReportReason::class)->find($reasonId);

            if (null !== $reason && 0 === count($reason->getChildren())) {
                $report = (new Report())->setType('post')->setPost($post)->setReason($reason)->setUser($this->getUser());

                $post->addReport($report);

                $this->addFlash('warning', 'Post successfully reported. We will review this post shortly.');

                $userPypPosts = $userPypPostRepository->findBy([
                    'user' => $this->getUser(),
                    'post' => $post
                ]);

                if (!empty($userPypPosts)) {
                    $em->remove($userPypPosts[0]);
                    $em->flush();
                }

                return $this->redirectToRoute('app_index');
            }
        }

        return $this->render('post/report.html.twig', [
            'reportReasons' => $em->getRepository(ReportReason::class)->findAll(),
            'post' => $post,
            'user' => $post->getUser(),
            'reasonId' => $reasonId,
        ]);
    }
}
