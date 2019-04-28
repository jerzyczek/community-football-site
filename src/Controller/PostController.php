<?php

namespace App\Controller;

use App\Entity\Group;
use App\Entity\Image;
use App\Entity\Post;
use App\Form\PostType;
use App\Repository\GroupRepository;
use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("group/{groupId}/post")
 */
class PostController extends AbstractController
{
    /**
     * @Route("/", name="post_index", methods={"GET"})
     */
    public function index(GroupRepository $groupRepository, Request $request): Response
    {
        $group = $groupRepository->find($request->get('groupId'));

        return $this->render('post/index.html.twig', [
            'posts' => $group->getPosts(),
        ]);
    }

    /**
     * @Route("/new", name="post_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $post = new Post();
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();

            $post->setUser($this->getUser());

            $group = $this->getDoctrine()->getRepository(Group::class)->find($request->get('groupId'));

            $post->setGroup($group);

            $files = $request->files->get('post')['images'];

            /** @var UploadedFile $file */
            foreach ($files as $file) {
                $image = new Image();

                $filename = md5(uniqid()) . '.' . $file->guessClientExtension();

                $image->setFilename($filename);
                $image->setPath('/asset/images/' . $filename);

                $file->move($this->getParameter('image_directory'), $filename);

                $image->setPost($post);

                $image->setDateOfCreation(new \DateTime());

                $post->setImages($image);

                $entityManager->persist($post);
                $entityManager->flush();
            }
            return $this->redirectToRoute('post_index', ['groupId' => $request->get('groupId')]);
        }
        return $this->render('post/new.html.twig', [
            'post' => $post,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="post_show", methods={"GET"})
     */
    public function show(Post $post): Response
    {
        return $this->render('post/show.html.twig', [
            'post' => $post,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="post_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Post $post): Response
    {
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('post_index', [
                'id' => $post->getId(),
            ]);
        }

        return $this->render('post/edit.html.twig', [
            'post' => $post,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="post_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Post $post): Response
    {
        if ($this->isCsrfTokenValid('delete'.$post->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($post);
            $entityManager->flush();
        }

        return $this->redirectToRoute('post_index');
    }

    /**
     * @Route("/{id}/postView", name="post_client_main", methods={"GET"})
     */
    public function getPostUserView(Request $request)
    {
        $post = $this->getDoctrine()->getRepository(Post::class)->find($request->get('id'));

        dump($post);
        die;
    }
}
