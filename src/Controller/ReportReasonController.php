<?php

namespace App\Controller;

use App\Repository\ReportReasonRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class ReportReasonController extends AbstractController
{
    public function renderReasonTree(ReportReasonRepository $reportReasonRepository, int $reportReasonParentId = 0, int $postId = 0, int $commentId = 0): Response
    {
        if (0 === $reportReasonParentId) {
            return $this->render('report_reason/_reason_tree.html.twig', [
                'reportReasons' => $reportReasonRepository->getTopLevelReportReasons(),
                'reportReasonParentId' => null,
                'postId' => $postId,
                'commentId' => $commentId,
            ]);
        }

        return $this->render('report_reason/_reason_tree.html.twig', [
            'reportReasons' => $reportReasonRepository->find($reportReasonParentId)->getChildren(),
            'reportReasonParentId' => $reportReasonParentId,
            'postId' => $postId,
            'commentId' => $commentId,
        ]);
    }
}
