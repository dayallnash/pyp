<?php

namespace App\Controller;

use App\Entity\Interaction;
use App\Entity\Post;
use App\Entity\UserInfluence;
use App\Repository\ReportRepository;
use App\Repository\UserPypPostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class MyAccountController extends AbstractController
{
    /**
     * @Route("/my/account", name="my_account")
     */
    public function index(Request $request, EntityManagerInterface $em): Response
    {
        if (null === $this->getUser()) {
            return $this->redirectToRoute('app_home');
        }

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

    /**
     * @Route("/my/account/change-password", name="my_account_change_password")
     */
    public function changePassword(
        Request $request,
        UserPasswordHasherInterface $passwordHasher,
        EntityManagerInterface $em
    ): Response {
        if ($request->isMethod(Request::METHOD_GET)) {
            return $this->render('my_account/change_password.html.twig', [
                'user' => $this->getUser(),
            ]);
        }

        $currentPassword = $request->request->filter('current_password', '', FILTER_SANITIZE_STRING);
        $newPassword1 = $request->request->filter('new_password_1', '', FILTER_SANITIZE_STRING);
        $newPassword2 = $request->request->filter('new_password_2', '', FILTER_SANITIZE_STRING);

        $user = $this->getUser();

        if (!$passwordHasher->isPasswordValid($user, $currentPassword)) {
            $this->addFlash('danger', 'Something went wrong. Try again!');

            return $this->redirectToRoute('my_account_change_password');
        }

        if ($newPassword1 !== $newPassword2) {
            $this->addFlash('danger', 'Your two new passwords don\'t match. Try again!');

            return $this->redirectToRoute('my_account_change_password');
        }

        $hashedPassword = $passwordHasher->hashPassword($user, $newPassword1);

        if ($user->getPassword() === $hashedPassword) {
            $this->addFlash('danger', 'Something went wrong. Try again');

            return $this->redirectToRoute('my_account_change_password');
        }

        $user->setPassword($hashedPassword);

        $em->persist($user);
        $em->flush();

        $this->addFlash('success', 'Password updated!');

        return $this->redirectToRoute('my_account');
    }

    /**
     * @Route("/my/account/delete-account", name="my_account_delete_account")
     */
    public function deleteAccount(
        Request $request,
        EntityManagerInterface $em,
        ReportRepository $reportRepo,
        UserPypPostRepository $userPypPostRepo
    ): Response {
        if ($request->isMethod(Request::METHOD_GET)) {
            return $this->render('my_account/delete_account.html.twig', [
                'user' => $this->getUser(),
            ]);
        }

        $user = $this->getUser();

        $comments = [];
        $posts = [];

        /** @var Post $post */
        foreach ($user->getPosts() as $post) {
            foreach ($post->getInteractions() as $interaction) {
                if ('comment' === $interaction->getType()) {
                    $comments[] = $interaction->getId();
                }

                $em->remove($interaction);
            }

            $posts[] = $post->getId();

            $em->remove($post);
        }

        foreach ($userPypPostRepo->findBy(['user' => $user]) as $userPypPost) {
            $em->remove($userPypPost);
        }

        foreach ($userPypPostRepo->findBy(['post' => $posts]) as $userPypPost) {
            $em->remove($userPypPost);
        }

        foreach ($em->getRepository(Interaction::class)->findBy(['user' => $user]) as $interaction) {
            if ('comment' === $interaction->getType()) {
                $commentIds[] = $interaction->getId();
            }

            $em->remove($interaction);
        }

        foreach ($reportRepo->findBy(['user' => $user]) as $report) {
            $em->remove($report);
        }

        foreach ($reportRepo->findBy(['post' => $posts]) as $report) {
            $em->remove($report);
        }

        foreach ($reportRepo->findBy(['comment' => $comments]) as $report) {
            $em->remove($report);
        }

        $influence = $em->getRepository(UserInfluence::class)->findOneBy(['user' => $user]);
        if (null !== $influence) {
            $em->remove($influence);
        }

        $em->remove($user);

        $em->flush();

        $this->get('security.token_storage')->setToken(null);
        $request->getSession()->invalidate();

        return $this->redirectToRoute('app_home');
    }
}
