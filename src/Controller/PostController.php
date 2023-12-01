<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\PostType;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityNotFoundException;
use Pagerfanta\Pagerfanta;
use Pagerfanta\Doctrine\ORM\QueryAdapter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PostController extends AbstractController
{
    public function __construct(
        private PostRepository $postRepository,
        private EntityManagerInterface $entityManager,
    ) {
    }

    #[Route('/post/reply/{id<\d+>}', name: 'app_post_reply')]
    public function reply(int $id, Request $request): Response
    {
        $parent = $this->postRepository->find($id);

        if (!$parent) {
            throw new EntityNotFoundException();
        }

        $post = new Post();
        $post->setParent($parent);
        return $this->processPost($post, $request);
    }

    private function processPost(Post $post, Request $request) : Response
    {
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $item = $form->getData();
            if (!$item->getAuthor() || !$this->isGranted('ROLE_ADMIN')) {
                $item->setAuthor($this->getUser());
            }

            if( !$post || !$post->getId() ) {
                $this->entityManager->persist($item);
            }
            $this->entityManager->flush();
            $this->addFlash('success', 'Post is saved!');
            return $this->redirectToRoute('app_post', ['slug' => $item->getSlug()]);
        }
        return $this->render('post/edit.html.twig', [
            'postForm' => $form->createView(),
        ]);
    }
    #[Route('/post/edit/{id<\d+>}', name: 'app_post_edit')]
    public function edit(int $id, Request $request): Response
    {
        $post = $this->postRepository->find($id);

        if ($id && !$post) {
            throw new EntityNotFoundException();
        }

        if ($post) {
            $this->denyAccessUnlessGranted('POST_EDIT', $post);
        }
        return $this->processPost($post, $request);
    }

    #[Route('/postItem/delete/{id}', name: 'app_post_delete')]
    public function delete(int $id): Response
    {
        $post = $this->postRepository->find($id);
        if (!$post) {
            throw new EntityNotFoundException();
        }
        $parentSlag =  $post->getParent()->getSlug() ?? '';
        $this->denyAccessUnlessGranted('POST_EDIT', $post);
        $this->entityManager->remove($post);
        $this->entityManager->flush();
        $this->addFlash('success', 'Past was removed!');

        return $this->redirectToRoute('app_post', ['slug' => $parentSlag]);
    }
    #[Route('/postItem/{slug}/{page<\d+>}', name: 'app_post')]
    public function index(string $slug, int $page = 1): Response
    {
        $post = $this->postRepository->findOneBySlug($slug);
        return $this->renderPosts($page, $post);
    }

    #[Route('/posts/{page<\d+>}', name: 'app_posts')]
    public function posts(PostRepository $postRepository, int $page = 1): Response
    {
        return $this->renderPosts($page);
    }
    private function renderPosts(int $page, $post = null)
    {
        $pagerfanta = new Pagerfanta(
            new QueryAdapter($this->postRepository->createQueryBuilderByParent($post))
        );
        $pagerfanta->setMaxPerPage(3);
        $pagerfanta->setCurrentPage($page);
        return $this->render('post/index.html.twig', [
            'post' => $post,
            'pager' => $pagerfanta
        ]);
    }
}
