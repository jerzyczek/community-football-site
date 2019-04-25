<?php

namespace App\Controller;

use App\Entity\Group;
use App\Entity\User;
use App\Form\GroupType;
use App\Repository\GroupRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/group")
 */
class GroupController extends AbstractController
{
    /**
     * @Route("/", name="group_index", methods={"GET"})
     */
    public function index(UserRepository $userRepository): Response
    {
        $userId = $this->getUser()->getId();
        $userGroups = $userRepository->find($userId);

        return $this->render('group/index.html.twig', [
            'groups' => $userGroups->getGroups(),
        ]);
    }

    /**
     * @Route("/new", name="group_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $group = new Group();

        $form = $this->createForm(GroupType::class, $group);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $group->setUser($this->getUser());
            $entityManager->persist($group);
            $entityManager->flush();

            return $this->redirectToRoute('group_index');
        }


        return $this->render('group/new.html.twig', [
            'group' => $group,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="group_show", methods={"GET"})
     */
    public function show(Group $group): Response
    {
        return $this->render('group/show.html.twig', [
            'group' => $group,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="group_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Group $group): Response
    {
        $form = $this->createForm(GroupType::class, $group);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('group_index', [
                'id' => $group->getId(),
            ]);
        }

        return $this->render('group/edit.html.twig', [
            'group' => $group,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="group_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Group $group): Response
    {
        if ($this->isCsrfTokenValid('delete'.$group->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($group);
            $entityManager->flush();
        }

        return $this->redirectToRoute('group_index');
    }

    /**
     * @Route("/{id}/groupView", name="group_client_main", methods={"GET"})
     */
    public function getGroupUserView(Request $request)
    {
        $group = $this->getDoctrine()->getRepository(Group::class)->find($request->get('id'));
        return $this->render('group/mainGroup.html.twig', [
            'group' => $group
        ]);
    }
}
