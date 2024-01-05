<?php

namespace App\Controller\Backend;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    /**
     * @Route("/hose/user/view", name="hose_user_view")
     *
     * @param Request        $request
     * @param UserRepository $userRepo
     *
     * @return Response
     */
    public function userIndex(Request $request, UserRepository $userRepo): Response
    {
        $userId = $request->query->filter('userId', 0, FILTER_SANITIZE_NUMBER_INT);

        if (!empty($userId)) {
            $user = $this->getDoctrine()->getRepository(User::class)->find($userId);
        } else {
            $user = $this->getUser();
        }

        return $this->render('admin/user/index.html.twig', [
            'currentUser' => $user,
            'userGenerator' => $userRepo->findAllAsGenerator(),
        ]);
    }

    /**
     * @Route("/hose/user/delete", name="hose_user_delete")
     *
     * @param Request        $request
     * @param UserRepository $userRepo
     *
     * @return Response
     */
    public function userDelete(Request $request, UserRepository $userRepo): Response
    {
        $result = $userRepo->delete((int) $request->query->filter('userId', '0', FILTER_SANITIZE_NUMBER_INT));

        if ($result) {
            $this->addFlash('success', 'User has been deleted');
        } else {
            $this->addFlash('danger', 'User has not been deleted');
        }

        return $this->redirectToRoute('hose_user_view');
    }

    /**
     * @Route("/hose/user/update", name="hose_user_update")
     *
     * @param Request                $request
     * @param EntityManagerInterface $em
     *
     * @return Response
     */
    public function userUpdate(Request $request, EntityManagerInterface $em): Response
    {
        $user = $em->getRepository(User::class)->find((int) $request->request->filter('id', '0',
            FILTER_SANITIZE_NUMBER_INT));

        if (null === $user) {
            $this->addFlash('danger', 'Could not find user to update');

            return $this->redirectToRoute('hose_user_view');
        }

        $user->setUsername($request->request->filter(
            'username',
            $user->getUsername(),
            FILTER_SANITIZE_STRING,
            [FILTER_FLAG_STRIP_HIGH, FILTER_FLAG_STRIP_LOW]
        ))->setHoseUser($request->request->filter('hose_user', $user->getHoseUser(), FILTER_VALIDATE_INT));

        $em->persist($user);
        $em->flush();

        return $this->redirectToRoute('hose_user_view');
    }
}
