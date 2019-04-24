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
        return $this->render('dashboard_page.html.twig');
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
