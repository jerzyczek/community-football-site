<?php

namespace App\Controller;

use App\Entity\Chat;
use App\Entity\User;
use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 *
 */
class ChatController extends AbstractController
{
    /**
     * @Route("group/{groupId}/post/{postId}/comment/", name="comment_index", methods={"GET"})
     */
    public function index(PostRepository $postRepository, Request $request): Response
    {
        $comment = $postRepository->find($request->get('postId'));

        return $this->render('comment/index.html.twig', [
            'comments' => $comment->getComments(),
        ]);
    }

    /**
     * @Route("chat/history", name="chat_history", methods={"GET"})
     */
    public function getHistory(Request $request)
    {
        $user2 = $request->get('userId');
        $repository = $this->getDoctrine()->getRepository(Chat::class);
        $messageHistory = $repository->getHistoryQuery($this->getUser(), $user2);
        if(count($messageHistory->getResult()) < 1)
        {
            $manager = $this->getDoctrine()->getManager();
            $chat = new Chat();
            $chat->setUser1($this->getUser()->getId());
            $chat->setUser2($user2);
            $manager->persist($chat);
            $manager->flush();
        }

        $chat = $chat ?? $messageHistory->getSingleResult();

        return $this->render('chat/history.html.twig', [
            'chat' => $chat,
            'messages' => $chat->getMessages(),
        ]);
    }

    /**
     * @Route("chat/message/add", name="chat_add_message", methods={"POST"})
     */
    public function addMessageAction(Request $request)
    {
        //TODO Validate if correct user is adding message
//        $user2 = $request->get('userId');

        $chat = $this->getDoctrine()->getRepository(Chat::class)->find($request->get('chatid'));
        $em = $this->getDoctrine()->getManager();
        $chat->addMessage($this->getUser()->getId(), $request->get('message'));

        $em->persist($chat);

        $em->flush();

        return new JsonResponse(['added' => true, 'userid' => $this->getUser()->getId()]);
    }
}
