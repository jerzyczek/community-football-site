<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Post;
use App\Form\CommentType;
use App\Form\PostType;
use App\Repository\CommentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 *
 */
class CommentController extends AbstractController
{
    /**
     * @Route("group/{groupId}/post/{postId}/comment/", name="comment_index", methods={"GET"})
     */
    public function index(CommentRepository $commentRepository): Response
    {
        return $this->render('comment/index.html.twig', [
            'comments' => $commentRepository->findAll(),
        ]);
    }

    /**
     * @Route("group/{groupId}/post/{postId}/comment/new", name="comment_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager = $this->getDoctrine()->getManager();
            $comment->setPost($this->getDoctrine()->getRepository(Post::class)->find($request->get('postId')));
            $comment->setUser($this->getUser());

            $entityManager->persist($comment);
            $entityManager->flush();

            return $this->redirectToRoute('comment_index', [
                'groupId' => $request->get('groupId'),
                'postId' => $request->get('postId'),
            ]);
        }

        return $this->render('comment/new.html.twig', [
            'comment' => $comment,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("group/{groupId}/post/{postId}/comment/{id}", name="comment_show", methods={"GET"})
     */
    public function show(Comment $comment): Response
    {
        return $this->render('comment/show.html.twig', [
            'comment' => $comment,
        ]);
    }

    /**
     * @Route("/comment/{id}/edit", name="comment_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Comment $comment): Response
    {
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('comment_index', [
                'id' => $comment->getId(),
            ]);
        }

        return $this->render('comment/edit.html.twig', [
            'comment' => $comment,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/comment/{id}", name="comment_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Comment $comment): Response
    {
        if ($this->isCsrfTokenValid('delete'.$comment->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($comment);
            $entityManager->flush();
        }

        return $this->redirectToRoute('comment_index');
    }

    /**
     * @Route("/comment/{id}/ajax", name="comment_delete_ajax", methods={"DELETE"})
     */
    public function deleteAjax(Request $request, Comment $comment): Response
    {
        if ($this->isCsrfTokenValid('delete'.$comment->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($comment);
            $entityManager->flush();
        }

        return JsonResponse::create('deleted');
    }

    /**
     * @Route("group/{groupId}/post/{postId}/comment/newAjax", name="comment_new", methods={"POST"})
     */
    public function newAjax(Request $request): Response
    {

        //TODO VALIDATE
        $entityManager = $this->getDoctrine()->getManager();
        $comment = new Comment();
        $comment->setUser($this->getUser());
        $comment->setPost($this->getDoctrine()->getRepository(Post::class)->find($request->get('postId')));
        $comment->setContent($request->get('content'));
        $entityManager->persist($comment);
        $entityManager->flush();

        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        return $this->render('comment/singleCommentView.html.twig', [
            'comment' => $comment
        ]);

    }

    /**
     * @Route("/comment/{id}/like", name="comment_like", methods={"POST"})
     */
    public function likeAction(Request $request, Comment $comment): Response
    {
        $comment->addReaction($this->getUser(), 'like');
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($comment);
        $entityManager->flush();

        return JsonResponse::create('added');
    }

    /**
     * @Route("/comment/{id}/unlike", name="comment_like", methods={"POST"})
     */
    public function unlikeAction(Request $request, Comment $comment): Response
    {
        $comment->removeReaction($this->getUser(), 'like');

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($comment);
        $entityManager->flush();

        return JsonResponse::create('added');
    }
}
