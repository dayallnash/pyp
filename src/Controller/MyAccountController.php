<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MyAccountController extends AbstractController
{
    /**
     * @Route("/my/account", name="my_account")
     */
    public function index(Request $request, EntityManagerInterface $em): Response
    {
        if ($request->isMethod(Request::METHOD_GET)) {
            return $this->render('my_account/index.html.twig', [
                'user' => $this->getUser(),
            ]);
        }

        $firstName = $request->request->filter('first_name', null, FILTER_SANITIZE_STRING);
        $lastName = $request->request->filter('last_name', null, FILTER_SANITIZE_STRING);
        $bio = $request->request->filter('bio', null, FILTER_SANITIZE_STRING);

        $user = $this->getUser();

        if (null !== $firstName && '' !== $firstName && $user->getFirstName() !== $firstName) {
            $user->setFirstName($firstName);
        } elseif ('' === $firstName && $user->getFirstName() !== $firstName) {
            $user->setFirstName(null);
        }

        if (null !== $lastName && '' !== $lastName && $user->getLastName() !== $lastName) {
            $user->setLastName($lastName);
        } elseif ('' === $lastName && $user->getLastName() !== $lastName) {
            $user->setLastName(null);
        }

        if (null !== $bio && '' !== $bio && $user->getBio() !== $bio) {
            $user->setBio($bio);
        } elseif ('' === $bio && $user->getBio() !== $bio) {
            $user->setBio(null);
        }

        $em->persist($user);
        $em->flush();

        $this->addFlash('info', 'Saved!');

        return $this->redirectToRoute('my_account');
    }
}
