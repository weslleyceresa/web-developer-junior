<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\PostModel;

class PostsController extends BaseController
{
    protected $postModel;

    public function __construct()
    {
        $this->postModel = new PostModel();
    }

    /**
     * Lista todos os posts.
     */
    public function index()
    {
        $posts = $this->postModel->findAll();
        return view('admin/posts_list', ['posts' => $posts]);
    }

    /**
     * Exibe formulário de novo post.
     */
    public function new()
    {
        return view('admin/post_form', [
            'title' => 'Novo Post',
            'post' => null,
            'action' => '/admin/posts/create'
        ]);
    }

    /**
     * Salva novo post.
     */
    public function create()
    {
        $data = $this->request->getPost();

        // Verifica se o usuário está logado
        $userId = session()->get('user_id');
        if (!$userId) {
            return redirect()->to('/login');
        }

        // Prepara os dados (sem slug — será gerado no model)
        $insertData = [
            'title'        => $data['title'] ?? '',
            'html_content' => $data['html_content'] ?? '',
            'status'       => 'published',
            'author_id'    => $userId
        ];

        // Tenta salvar
        if ($this->postModel->insert($insertData)) {
            return redirect()->to('/blog')->with('success', 'Post criado com sucesso!');
        }

        return redirect()->back()->withInput()->with('errors', $this->postModel->errors());
    }

    /**
     * Exibe formulário de edição.
     */
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

    /**
     * Atualiza um post existente.
     */
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
}
