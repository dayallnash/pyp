<?php

namespace App\Controller;

use App\Entity\UserPypPost;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PypController extends AbstractController
{
    /**
     * @Route("/get_user_pyp", name="app_get_user_pyp")
     *
     * @param EntityManagerInterface $em
     *
     * @return Response
     */
    public function getUserPyp(EntityManagerInterface $em): Response
    {
        $posts = $em->getRepository(UserPypPost::class)->getAllPostsForUser($this->getUser());

        return $this->render('pyp/_user_pyp.html.twig', ['posts' => $posts]);
    }

    /**
     * @Route("/load_unviewed_pyps", name="app_load_unviewed_pyps")
     *
     * @return Response
     */
    public function loadUnviewedPyps(): Response
    {
        return new Response();
    }
}