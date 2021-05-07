<?php

namespace App\Controller\Backend;

use App\Entity\User;
use App\Repository\MessengerMessagesRepository;
use App\Repository\UserRepository;
use App\Service\UserRetriever;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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

    /**
     * @Route("/hose/queue/view", name="hose_queue_view")
     *
     * @param MessengerMessagesRepository $messengerMessagesRepo
     *
     * @return Response
     */
    public function queueView(MessengerMessagesRepository $messengerMessagesRepo): Response
    {
        return $this->render('admin/queue/view.html.twig', [
            'messages' => $messengerMessagesRepo->findAllAsGenerator(),
        ]);
    }

    /**
     * @Route("/hose/queue/delete", name="hose_queue_delete")
     *
     * @param Request                     $request
     * @param MessengerMessagesRepository $messengerMessagesRepo
     *
     * @return Response
     */
    public function queueDelete(Request $request, MessengerMessagesRepository $messengerMessagesRepo): Response
    {
        $result = $messengerMessagesRepo->delete((int) $request->query->filter('messageId', '0', FILTER_SANITIZE_NUMBER_INT));

        if ($result) {
            $this->addFlash('success', 'Message has been deleted');
        } else {
            $this->addFlash('danger', 'Message has not been deleted');
        }

        return $this->redirectToRoute('hose_queue_view');
    }

    /**
     * @Route("/hose/user/view", name="hose_user_view")
     *
     * @param Request        $request
     * @param UserRetriever  $userRetriever
     * @param UserRepository $userRepo
     *
     * @return Response
     */
    public function userIndex(Request $request, UserRetriever $userRetriever, UserRepository $userRepo): Response
    {
        $userId = $request->query->filter('userId', 0, FILTER_SANITIZE_NUMBER_INT);

        if (!empty($userId)) {
            $user = $userRetriever->retrieve($userId);
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
        $user = $em->getRepository(User::class)->find((int) $request->request->filter('id', '0', FILTER_SANITIZE_NUMBER_INT));

        if (null === $user) {
            $this->addFlash('danger', 'Could not find user to update');

            return $this->redirectToRoute('hose_user_view');
        }

        $user->setUsername($request->request->filter('username', $user->getUsername(), FILTER_SANITIZE_STRING, [FILTER_FLAG_STRIP_HIGH, FILTER_FLAG_STRIP_LOW]));

        $em->persist($user);
        $em->flush();

        return $this->redirectToRoute('hose_user_view');
    }
}
