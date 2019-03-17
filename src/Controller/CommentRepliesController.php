<?php

namespace App\Controller;

use App\Entity\CommentReplies;
use App\Form\CommentRepliesType;
use App\Repository\CommentRepliesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/comment/replies")
 */
class CommentRepliesController extends AbstractController
{
    /**
     * @Route("/", name="comment_replies_index", methods={"GET"})
     */
    public function index(CommentRepliesRepository $commentRepliesRepository): Response
    {
        return $this->render('comment_replies/index.html.twig', [
            'comment_replies' => $commentRepliesRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="comment_replies_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $commentReply = new CommentReplies();
        $form = $this->createForm(CommentRepliesType::class, $commentReply);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($commentReply);
            $entityManager->flush();

            return $this->redirectToRoute('comment_replies_index');
        }

        return $this->render('comment_replies/new.html.twig', [
            'comment_reply' => $commentReply,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="comment_replies_show", methods={"GET"})
     */
    public function show(CommentReplies $commentReply): Response
    {
        return $this->render('comment_replies/show.html.twig', [
            'comment_reply' => $commentReply,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="comment_replies_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, CommentReplies $commentReply): Response
    {
        $form = $this->createForm(CommentRepliesType::class, $commentReply);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('comment_replies_index', [
                'id' => $commentReply->getId(),
            ]);
        }

        return $this->render('comment_replies/edit.html.twig', [
            'comment_reply' => $commentReply,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="comment_replies_delete", methods={"DELETE"})
     */
    public function delete(Request $request, CommentReplies $commentReply): Response
    {
        if ($this->isCsrfTokenValid('delete'.$commentReply->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($commentReply);
            $entityManager->flush();
        }

        return $this->redirectToRoute('comment_replies_index');
    }
}
