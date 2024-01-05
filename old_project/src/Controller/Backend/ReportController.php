<?php

namespace App\Controller\Backend;

use App\Entity\Report;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ReportController extends AbstractController
{
    /**
     * @Route("/hose/report/view", name="hose_report_view")
     *
     * @param EntityManagerInterface $em
     *
     * @return Response
     */
    public function viewReports(EntityManagerInterface $em): Response
    {
        return $this->render('admin/report/index.html.twig', [
            'reports' => $em->getRepository(Report::class)->findAll(),
        ]);
    }
}
