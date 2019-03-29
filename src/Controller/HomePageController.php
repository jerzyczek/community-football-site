<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class HomePageController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index()
    {
        if($this->getUser())
        {
            return $this->redirectToRoute('dashboard');
        }
        return $this->render('home_page.html.twig');
    }

    /**
     * @Route("/dashboard", name="dashboard")
     */
    public function dashboard()
    {
        return $this->render('dashboard_page.html.twig');
    }
}
