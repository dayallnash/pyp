<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class HomepageController extends AbstractController
{
    /**
     * @Route("/", name="app_index")
     *
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(Request $request)
    {
        return $this->render('homepage/index.html.twig');
    }

    /**
     * @Route("/privacy", name="app_privacy_policy")
     */
    public function privacyPolicy()
    {
        return $this->render('homepage/privacy.html.twig');
    }
}