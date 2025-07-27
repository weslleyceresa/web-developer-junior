<?php

namespace App\Controllers;

use App\Models\PostModel;
use CodeIgniter\Controller;
use CodeIgniter\Exceptions\PageNotFoundException;

class BlogController extends Controller
{
    private const POSTS_PER_PAGE = 5;
    private const SEARCH_RESULTS_LIMIT = 5;

    public function __construct()
    {
        helper('text');
    }

    /**
     * Exibe o formulário de criação de novo post
     * 
     * @return string
     */
    public function create()
    {
        return view('blog/create');
    }

    /**
     * Exibe a página inicial do blog com os posts mais recentes
     * 
     * @return string
     */
    public function index()
    {
        $postModel = new PostModel();
        $initialPosts = $postModel->getPaginatedWithAuthors(self::POSTS_PER_PAGE, 0);

        return view('blog/index', ['initialPosts' => $initialPosts]);
    }

    /**
     * Carrega mais posts via AJAX para paginação infinita
     * 
     * @return string
     */
    public function loadMore()
    {
        $page = (int) ($this->request->getGet('page') ?? 1);
        $offset = ($page - 1) * self::POSTS_PER_PAGE;

        $postModel = new PostModel();
        $posts = $postModel->getPaginatedWithAuthors(self::POSTS_PER_PAGE, $offset);

        return view('blog/_posts', ['posts' => $posts]);
    }

    /**
     * Exibe um post individual baseado no slug
     * 
     * @param string $slug Slug do post
     * @return string
     * @throws PageNotFoundException
     */
    public function show(string $slug)
    {
        $post = $this->getPostBySlug($slug);

        if (!$post) {
            throw PageNotFoundException::forPageNotFound('Post não encontrado');
        }

        return view('blog/detail', compact('post'));
    }

    /**
     * Realiza busca de posts baseado em query string
     * 
     * @return string
     */
    public function search()
    {
        $query = trim($this->request->getGet('q') ?? '');
        $results = [];

        if (!empty($query)) {
            $postModel = new PostModel();
            $results = $postModel->searchPosts($query, self::SEARCH_RESULTS_LIMIT);
        }

        return view('blog/index', [
            'initialPosts' => $results,
            'searchQuery' => $query
        ]);
    }

    /**
     * Obtém um post completo pelo slug com informações do autor
     * 
     * @param string $slug Slug do post
     * @return array|null
     */
    private function getPostBySlug(string $slug): ?array
    {
        $postModel = new PostModel();

        return $postModel
            ->select('posts.*, users.name AS author_name, users.username AS author_username, users.avatar_path')
            ->join('users', 'users.id = posts.author_id', 'left')
            ->where('posts.slug', $slug)
            ->where('posts.status', 'published')
            ->first();
    }
}
