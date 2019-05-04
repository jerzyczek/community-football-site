<?php

namespace App\Controller;

use App\Entity\Group;
use App\Entity\Image;
use App\Entity\Post;
use App\Form\PostType;
use App\Repository\GroupRepository;
use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Tests\Normalizer\ObjectSerializerNormalizer;


class PostController extends AbstractController
{
    /**
     * @Route("/group/{groupId}/post", name="post_index", methods={"GET"})
     */
    public function index(GroupRepository $groupRepository, Request $request): Response
    {
        $group = $groupRepository->find($request->get('groupId'));

        return $this->render('post/index.html.twig', [
            'posts' => $group->getPosts(),
        ]);
    }

    /**
     * @Route("/group/{groupId}/post/new", name="post_new", methods={"GET","POST"})
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

            if(!empty($files)) //TODO  tutaj jakiś serwis do tego mozna dać @mateusz
            {
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
                }
            }

            $entityManager->persist($post);
            $entityManager->flush();
            if((bool)$request->get('isAjax') === true)
            {
//                $encoders = [new XmlEncoder(), new JsonEncoder()]; //jakis serwis do tego
//                $normalizer = (new ObjectNormalizer());
//                $normalizer->setIgnoredAttributes(array('user', 'group', 'images', 'comments', 'createdAt', 'updatedAt'));
//                $normalizers = [$normalizer];
//
//                $serializer = new Serializer($normalizers, $encoders);
//                $jsonContent = $serializer->serialize($post, 'json');
//
//                return JsonResponse::fromJsonString($jsonContent);

                return $this->render("post/singlePost.html.twig", [
                    'post' => $post,
                ]);

            }
            return $this->redirectToRoute('post_index', ['groupId' => $request->get('groupId')]);
        }
        return $this->render('post/new.html.twig', [
            'post' => $post,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/group/{groupId}/post/{id}", name="post_show", methods={"GET"})
     */
    public function show(Post $post, Request $request): Response
    {
        return $this->render("post/show.html.twig", [
            'post' => $post,
        ]);
    }

    /**
     * @Route("/group/{groupId}/post/{id}/edit", name="post_edit", methods={"GET","POST"})
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
     * @Route("/group/{groupId}/post/{id}", name="post_delete", methods={"DELETE"})
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
     * @Route("/post/{id}/like", name="post_like", methods={"POST"})
     */
    public function likeAction(Request $request, Post $post): Response
    {
        if($post->isUserLikePost($this->getUser()) === true)
        {
            return new JsonResponse(['error' => "You already like this post"]);
        }

        $post->addReaction($this->getUser(), 'like');
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($post);
        $entityManager->flush();

        return new JsonResponse(['reactionAdded' => true]);
    }

    /**
     * @Route("/post/{id}/unlike", name="post_unlike", methods={"POST"})
     */
    public function unlikeAction(Request $request, Post $post): Response
    {
        if($post->isUserLikePost($this->getUser()) === false)
        {
            return new JsonResponse(['error' => "You already do not like this post"]);
        }
        $post->removeReaction($this->getUser(), 'like');
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($post);
        $entityManager->flush();

        return new JsonResponse(['reactionRemoved' => true]);
    }
}
