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

class QueueController extends AbstractController
{
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
}
