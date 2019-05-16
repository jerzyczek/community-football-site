<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ChangePasswordType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\User\UserChecker;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route("/account")
 */
class AccountController extends AbstractController
{
    /**
     * @Route("/", name="account_manage")
     */
    public function editPassword(Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        $user = $this->getUser();

        $form = $this->createForm(ChangePasswordType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $getForm = $form->getData();
            $oldPassword = $getForm->getOldPassword();

            $comparePasswords = $passwordEncoder->isPasswordValid($user, $oldPassword);

            if ($comparePasswords) {

                $password = $passwordEncoder->encodePassword($user, $user->getPlainPassword());

                $user->setPassword($password);

                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($user);
                $entityManager->flush();

                $this->addFlash(
                    'notice',
                    'Your password has been changed'
                );
            } else {
                $this->addFlash(
                    'notice',
                    'Old password is different'
                );
            }
        }

        return $this->render('account/profile.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }
}