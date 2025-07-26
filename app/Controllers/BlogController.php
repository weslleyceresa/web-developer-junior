<?php

namespace App\Controllers;

use App\Models\PostModel;
use CodeIgniter\Controller;

class BlogController extends Controller
{
    public function __construct()
    {
        // Carregar o helper 'text' para ter acesso à função character_limiter()
        helper('text');
    }

    public function create()
    {
        return view('blog/create');
    }

    public function index()
    {
        $postModel = new \App\Models\PostModel();

        // Carrega os primeiros posts (5 mais recentes)
        $initialPosts = $postModel->getPaginatedWithAuthors(5, 0);

        return view('blog/index', ['initialPosts' => $initialPosts]);
    }

    public function loadMore()
    {
        $page = $this->request->getGet('page') ?? 1;
        $perPage = 5;
        $offset = ($page - 1) * $perPage;

        $postModel = new \App\Models\PostModel();
        $posts = $postModel->getPaginatedWithAuthors($perPage, $offset);

        return view('blog/_posts', ['posts' => $posts]);
    }

    public function show($slug)
    {
        $postModel = new \App\Models\PostModel();

        $post = $postModel
            ->select('posts.*, users.name AS author_name, users.username AS author_username, users.avatar_path')
            ->join('users', 'users.id = posts.author_id', 'left')
            ->where('posts.slug', $slug)
            ->where('posts.status', 'published')
            ->first();

        if (!$post) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Post não encontrado');
        }

        return view('blog/detail', ['post' => $post]);
    }

    public function search()
    {
        $query = $this->request->getGet('q');
        $postModel = new \App\Models\PostModel();

        $results = [];
        if (!empty($query)) {
            $results = $postModel->searchPosts($query);
        }

        return view('blog/index', [
            'initialPosts' => $results,
            'searchQuery' => $query
        ]);
    }
}
