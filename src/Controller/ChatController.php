<?php

namespace App\Controller;

use App\Entity\Chat;
use App\Entity\User;
use App\Repository\ChatRepository;
use App\Repository\PostRepository;
use \Symfony\Component\Form\Exception\InvalidArgumentException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Validator\Exception\ValidatorException;

/**
 *
 */
class ChatController extends AbstractController
{
    /**
     * @var ChatRepository
     */
    private $chatRepository;

    public function __construct (ChatRepository $chatRepository)
    {
        $this->chatRepository = $chatRepository;
    }

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
    public function getHistoryAction(Request $request)
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
        $chat = $this->formatMessages($chat);

        return $this->render('chat/history.html.twig', [
            'chat' => $chat,
            'targetuserid' => $user2,
        ]);
    }

    /**
     * @Route("chat/message/add", name="chat_add_message", methods={"POST"})
     */
    public function addMessageAction(Request $request)
    {
        $chat = $this->chatRepository->find($request->get('chatid'));

        $loggedUser = $this->getUser();
        if($chat->getUser1() !== $loggedUser->getId() && $chat->getUser2() !== $loggedUser->getId())
        {
            throw new AccessDeniedException('Access Denied. You can not add message. Its not you!');
        }
        if($request->get('message') === '')
        {
            throw new InvalidArgumentException("Message is empty");
        }

        $em = $this->getDoctrine()->getManager();
        $chat->addMessage($this->getUser()->getId(), $request->get('message'));
        $em->persist($chat);
        $em->flush();

        return new JsonResponse([
            'added' => true,
            'targetuserid' => $request->get('targetuserid')
        ]);
    }

    /**
     * @Route("chat/refresh", name="chat_refresh", methods={"GET"})
     */
    public function refreshAction(Request $request)
    {
        $chat = $this->chatRepository->find($request->get('chatid'));
        $chat = $this->formatMessages($chat);

        return $this->render('chat/refresh.html.twig', [
            'chat' => $chat,
        ]);
    }

    /**
     * @param Chat $chat
     * @return Chat
     */
    private function formatMessages(Chat $chat) : Chat
    {
        $users = $this->getDoctrine()->getRepository(User::class)->getUsersByIdsIndexed([$chat->getUser1(), $chat->getUser2()]);

        $newMessages = [];
        foreach ($chat->getMessages() as $message)
        {
            if(!isset($users[$message['user']]))
            {
                continue;
            }
            $message['user'] = $users[$message['user']];
            $newMessages[] = $message;
        }
        $chat->setMessages($newMessages);

        return $chat;
    }
}

