<?php

namespace App\Controller;

use App\Entity\Chat;
use App\Entity\Comment;
use App\Form\ChatType;
use App\Repository\ChatRepository;
use App\Repository\GroupRepository;
use App\Repository\PostRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class HomePageController extends AbstractController
{
    protected $postRepository;

    public function __construct(
        PostRepository $postRepository
    ) {
        $this->postRepository = $postRepository;
    }

    /**
     * @Route("/", name="home")
     */
    public function index()
    {
        if($user = $this->getUser())
        {
            $this->logUserAction();
            return $this->redirectToRoute('dashboard');
        }

        return $this->redirectToRoute('security_login');
    }

    /**
     * @Route("/dashboard", name="dashboard")
     */
    public function dashboard(ChatRepository $chatRepository)
    {
        if(!$this->getUser())
        {
            return $this->redirectToRoute('security_login');
        }
        $this->logUserAction();
        $posts = $this->postRepository->getAllPosts($this->getUser());

        $newestPostsFromMemberGroups = $this->postRepository->getNewestPosts($this->getUser());

        $chatForm = $this->createForm(ChatType::class, new Chat());

        $chats = $this->getUser()->getChats();

        return $this->render('dashboard_page.html.twig', [
            'posts' => $posts,
            'newestPosts' => $newestPostsFromMemberGroups,
            'chatForm' => $chatForm->createView(),
            'chats' => $chats
        ]);
    }

    private function logUserAction()
    {
        if($user = $this->getUser())
        {
            $em = $this->getDoctrine()->getManager();
            $user->setLastActivityAt(new \DateTime());
            $em->persist($user);
            $em->flush();
        }
    }
}