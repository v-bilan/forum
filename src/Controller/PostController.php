<?php

namespace App\Controller;

use App\Repository\PostRepository;
use Pagerfanta\Pagerfanta;
use Pagerfanta\Doctrine\ORM\QueryAdapter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PostController extends AbstractController
{
    public function __construct(private PostRepository $postRepository)
    {

    }
    #[Route('/post/{slug}/{page<\d+>}', name: 'app_post')]
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
            'replies' => $pagerfanta
        ]);
    }
}
