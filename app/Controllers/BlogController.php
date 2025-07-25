<?php

namespace App\Controllers;

use App\Models\PostModel;
use CodeIgniter\Controller;

class BlogController extends Controller
{

    public function create()
    {
        return view('blog/create');
    }

    public function saveDraft()
    {
        $data = $this->request->getPost();

        $drafts = session()->get('drafts') ?? [];
        $drafts[] = [
            'title' => $data['title'] ?? '',
            'content' => $data['content'] ?? ''
        ];
        session()->set('drafts', $drafts);

        return $this->response->setJSON(['status' => 'saved']);
    }

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

    public function savePost()
    {
        // Verificar se usuário está logado
        if (!session()->get('user_id')) {
            return redirect()->to('/login');
        }

        // Validar dados
        $validation = \Config\Services::validation();
        $validation->setRules([
            'title' => 'required|min_length[3]',
            'html_content' => 'required|min_length[10]'
        ]);

        if (!$validation->run($this->request->getPost())) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        // Salvar post
        $postModel = new PostModel();
        $postModel->save([
            'title' => $this->request->getPost('title'),
            'html_content' => $this->request->getPost('html_content'),
            'author_id' => session()->get('user_id'),
            'status' => 'published'
        ]);

        return redirect()->to('/blog')->with('success', 'Postagem criada com sucesso!');
    }
}
