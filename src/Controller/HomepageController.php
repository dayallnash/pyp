<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class HomepageController extends AbstractController
{
    /**
     * @Route("/home", name="app_home")
     */
    public function index()
    {
        if (null !== $this->getUser()) {
            return $this->redirectToRoute('app_index');
        }

        return $this->render('homepage/index.html.twig', ['isHome' => true]);
    }
}
