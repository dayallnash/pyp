<?php

namespace App\Controller;

use App\Entity\Interaction;
use App\Entity\Report;
use App\Repository\InteractionRepository;
use App\Repository\PostRepository;
use App\Repository\ReportReasonRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class InteractionController extends AbstractController
{
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
            ->setUser($this->getUser())
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

        $interaction = $interactionRepo->findOneBy(['type' => 'like', 'post' => $post, 'user' => $this->getUser()]);

        if (null !== $interaction) {
            $em->remove($interaction);
            $em->flush();

            return $this->json(['success' => true]);
        }

        $interaction = (new Interaction())
            ->setPost($post)
            ->setUser($this->getUser())
            ->setType('like');

        $em->persist($interaction);
        $em->flush();

        return $this->json(['success' => true]);
    }

    /**
     * @Route("/comment/report/{commentId}", requirements={"commentId"="\d+"}, name="report_comment")
     *
     * @param Request                $request
     * @param EntityManagerInterface $em
     * @param InteractionRepository  $interactionRepo
     * @param ReportReasonRepository $reportReasonRepo
     * @param int                    $commentId
     *
     * @return Response
     */
    public function reportComment(
        Request $request,
        EntityManagerInterface $em,
        InteractionRepository $interactionRepo,
        ReportReasonRepository $reportReasonRepo,
        int $commentId = 0
    ): Response {
        $comment = $interactionRepo->find($commentId);

        if (null === $comment) {
            $this->addFlash('danger', 'Could not find comment to report.');

            return $this->redirectToRoute('app_index');
        }

        $reasonId = $request->request->filter('reportReasonParentId', 0, FILTER_SANITIZE_NUMBER_INT);

        if (!empty($reasonId)) {
            $reason = $reportReasonRepo->find($reasonId);

            if (null !== $reason && 0 === count($reason->getChildren())) {
                $report = (new Report())->setType('comment')->setComment($comment)->setReason($reason)->setUser($this->getUser());

                $comment->addReport($report);

                $em->persist($report);
                $em->persist($comment);

                $em->flush();

                $this->addFlash('warning', 'Comment successfully reported. We will review this comment shortly.');

                return $this->redirectToRoute('app_index');
            }
        }

        return $this->render('interaction/report.html.twig', [
            'reportReasons' => $reportReasonRepo->findAll(),
            'comment' => $comment,
            'user' => $comment->getUser(),
            'reasonId' => $reasonId,
        ]);
    }

    /**
     * @Route("/comment/report/undo", name="undo_report_comment")
     *
     * @param Request                $request
     * @param EntityManagerInterface $em
     *
     * @return JsonResponse
     */
    public function undoReportComment(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $commentId = $request->request->filter('commentId', 0, FILTER_VALIDATE_INT);

        if (empty($commentId)) {
            return $this->json(['success' => false]);
        }

        $comment = $em->getRepository(Interaction::class)->find($commentId);

        if (null === $comment) {
            return $this->json(['success' => false]);
        }

        $report = $em->getRepository(Report::class)->findOneBy([
            'comment' => $comment,
            'user' => $this->getUser(),
        ]);

        if (null !== $report) {
            $comment->removeReport($report);
            $em->remove($report);
            $em->flush();
        }

        return $this->json(['success' => true]);
    }
}
