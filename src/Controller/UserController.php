<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserController extends AbstractController
{
    /**
     * @Route("/user/online", name="user_online")
     */
    public function onlineUsersAction(Request $request, UserRepository $userRepository)
    {
        $users = $userRepository->getAllWithoutLogged($this->getUser());

        return $this->render('user/onlineSidebar.html.twig', [
            'users' => $users
        ]);
    }
}
