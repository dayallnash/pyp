<?php

namespace App\Controller;

use App\Entity\UserPipePost;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PipeController extends AbstractController
{
    /**
     * @Route("/get_user_pipe", name="app_get_user_pipe")
     *
     * @param EntityManagerInterface $em
     *
     * @return Response
     */
    public function getUserPipe(EntityManagerInterface $em): Response
    {
        $posts = $em->getRepository(UserPipePost::class)->getAllPostsForUser($this->getUser());

        return $this->render('pipe/_user_pipe.html.twig', ['posts' => $posts]);
    }

    /**
     * @Route("/load_unviewed_pipes", name="app_load_unviewed_pipes")
     *
     * @return Response
     */
    public function loadUnviewedPipes(): Response
    {
        return new Response();
    }
}