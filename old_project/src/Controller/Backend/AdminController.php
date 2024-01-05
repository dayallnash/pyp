<?php

namespace App\Controller\Backend;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    /**
     * @Route("/hose", name="hose")
     */
    public function index(): Response
    {
        return $this->render('admin/index.html.twig', [
            'loggedInAdminUser' => in_array('ROLE_ADMIN', $this->getUser()->getRoles(), true),
        ]);
    }
}
