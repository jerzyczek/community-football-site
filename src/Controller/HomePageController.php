<?php

namespace App\Controller;

use App\Entity\Group;
use App\Entity\User;
use App\Repository\GroupRepository;
use App\Repository\PostRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class HomePageController extends AbstractController
{
    protected $postRepository;
    protected $groupRepository;
    protected $userRepository;

    public function __construct(
        PostRepository $postRepository,
        GroupRepository $groupRepository,
        UserRepository $userRepository
    ) {
        $this->postRepository = $postRepository;
        $this->groupRepository = $groupRepository;
        $this->userRepository = $userRepository;
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
        return $this->render('home_page.html.twig');
    }

    /**
     * @Route("/dashboard", name="dashboard")
     */
    public function dashboard()
    {
        $this->logUserAction();
        $user = $this->userRepository->findById($this->getUser()->getId());

        $groupsId = [];

        $userGroupsMember = $user[0]->getGroupsMember();
        $userGroupsOwner = $user[0]->getGroups();

        foreach ($userGroupsMember as $member){
            $groupsId[] = $member->getId();
        }

        foreach ($userGroupsOwner as $owner){
            $groupsId[] = $owner->getId();
        }

        $groups = $this->groupRepository->findBy(array('id' => $groupsId));

        $sortedPosts = [];

        foreach ($groups as $group) {
            foreach ($group->getPosts() as  $post){
                $sortedPosts[] = $post;
            }
        }

        usort($sortedPosts, array($this, "comparator"));

        return $this->render('dashboard_page.html.twig', [
            'posts' => $sortedPosts
        ]);
    }

    function comparator($object1, $object2) {
        return $object1->getCreatedAt() < $object2->getCreatedAt();
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