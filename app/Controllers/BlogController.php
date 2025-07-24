<?php

namespace App\Controllers;

use App\Models\PostModel;
use CodeIgniter\Controller;

class BlogController extends Controller
{
    public function index()
    {
        return view('blog/index');
    }

    public function loadMore()
    {
        $page = $this->request->getGet('page') ?? 1;
        $perPage = 5;
        $offset = ($page - 1) * $perPage;

        $postModel = new PostModel();
        $posts = $postModel
            ->where('status', 'published')
            ->orderBy('created_at', 'DESC')
            ->findAll($perPage, $offset);

        return view('blog/_posts', ['posts' => $posts]);
    }
}
