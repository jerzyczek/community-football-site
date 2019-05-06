<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Post;
use App\Form\CommentType;
use App\Repository\CommentRepository;
use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
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
    public function index(PostRepository $postRepository, Request $request): Response
    {
        $comment = $postRepository->find($request->get('postId'));

        return $this->render('comment/index.html.twig', [
            'comments' => $comment->getComments(),
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
     * @Route("/post/{postId}/comment/newAjax", name="comment_new", methods={"POST"})
     */
    public function newAjax(Request $request, CommentRepository $commentRepository, PostRepository $postRepository): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $comment = new Comment();
        $comment->setUser($this->getUser());
        $comment->setPost($postRepository->find($request->get('postId')));
        $comment->setContent($request->get('content'));

        $commentid = null;
        if($commentid = $request->get('commentid'))
        {
            $parentComment = $commentRepository->find($commentid);
            $comment->setParentComment($parentComment);
        }

        $entityManager->persist($comment);
        $entityManager->flush();

        $template = 'comment/singleCommentView.html.twig';
        $data = [
            'comment' => $comment
        ];
        if($commentid !== null)
        {
            $template = 'comment/childComment.html.twig';
            $data = [
                'childComment' => $comment
            ];
        }

        return $this->render($template, $data);

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

        return $this->render('comment/singleCommentView.html.twig', [
            'comment' => $comment
        ]);
    }

    /**
     * @Route("/comment/{id}/unlike", name="comment_unlike", methods={"POST"})
     */
    public function unlikeAction(Request $request, Comment $comment): Response
    {
        $comment->removeReaction($this->getUser(), 'like');

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($comment);
        $entityManager->flush();

        return $this->render('comment/singleCommentView.html.twig', [
            'comment' => $comment
        ]);
    }
}
