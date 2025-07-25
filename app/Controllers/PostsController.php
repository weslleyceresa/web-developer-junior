<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\PostModel;

class PostsController extends BaseController
{
    protected $postModel;

    public function __construct()
    {
        helper('url');
        $this->postModel = new PostModel();
    }

    public function index()
    {
        $posts = $this->postModel->findAll();
        return view('admin/posts_list', ['posts' => $posts]);
    }

    public function new()
    {
        return view('admin/post_form', [
            'title' => 'Novo Post',
            'post' => null,
            'action' => '/admin/posts/create'
        ]);
    }

    /**
     * Método unificado para criar posts (admin e usuários comuns)
     */
    public function store()
    {
        if (!session()->get('user_id')) {
            return redirect()->to('/login');
        }

        $title = $this->request->getPost('title');
        $htmlContent = $this->request->getPost('html_content');
        $authorId = session()->get('user_id');

        $slugBase = url_title($title, '-', true);
        $slug = $this->generateUniqueSlug($slugBase);

        $postData = [
            'title'        => $title,
            'slug'         => $slug,
            'html_content' => $htmlContent,
            'author_id'    => $authorId,
            'status'       => 'published'
        ];

        $validation = \Config\Services::validation();
        $validation->setRules((new PostModel())->getValidationRules());

        if (!$validation->run($postData)) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        if (!$this->postModel->insert($postData)) {
            return redirect()->back()->withInput()->with('errors', $this->postModel->errors());
        }

        return redirect()->to('/blog')->with('success', 'Post criado com sucesso!');
    }

    public function edit($id)
    {
        $post = $this->postModel->find($id);
        if (!$post) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound("Post não encontrado");
        }

        return view('admin/post_form', [
            'title'  => 'Editar Post',
            'post'   => $post,
            'action' => "/admin/posts/update/{$id}"
        ]);
    }

    public function update($id)
    {
        $data = $this->request->getPost();

        $validation = \Config\Services::validation();
        $validation->setRules([
            'title'   => 'required|min_length[3]',
            'content' => 'required|min_length[10]'
        ]);

        if (!$validation->run($data)) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        $updateData = [
            'title'        => $data['title'],
            'html_content' => $data['content'],
            'updated_at'   => date('Y-m-d H:i:s')
        ];

        $this->postModel->update($id, $updateData);

        return redirect()->to('/admin/posts')->with('success', 'Post atualizado com sucesso!');
    }

    private function generateUniqueSlug(string $slugBase): string
    {
        $slug = $slugBase;
        $counter = 1;

        while ($this->postModel->where('slug', $slug)->first()) {
            $slug = $slugBase . '-' . $counter;
            $counter++;
        }

        return $slug;
    }
}
