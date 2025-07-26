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
        $search = $this->request->getGet('search');

        $builder = $this->postModel
            ->select('posts.*, users.name AS author_name, users.username AS author_username, users.avatar_path')
            ->join('users', 'users.id = posts.author_id', 'left');

        if ($search) {
            $builder->groupStart()
                ->like('posts.title', $search)
                ->orLike('users.name', $search)
                ->groupEnd();
        }

        $posts = $builder->orderBy('posts.created_at', 'DESC')->findAll();

        return view('admin/posts_list', [
            'posts' => $posts,
            'search' => $search
        ]);
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

        helper('text');
        $slugBase = url_title(convert_accented_characters($this->removeEmojis($title)), '-', true);
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

    public function delete($id)
    {
        $post = $this->postModel->find($id);

        if (!$post) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound("Post não encontrado.");
        }

        $this->postModel->delete($id);

        return redirect()->to('/admin/posts')->with('success', 'Post excluído com sucesso!');
    }

    public function deleteFromProfile($id)
    {
        $userId = session()->get('user_id');
        if (!$userId) {
            return redirect()->to('/login');
        }

        $post = $this->postModel->find($id);

        if (!$post) {
            return redirect()->back()->with('error', 'Post não encontrado.');
        }

        if ($post['author_id'] !== $userId) {
            return redirect()->back()->with('error', 'Você não tem permissão para excluir este post.');
        }

        $this->postModel->delete($id);

        return redirect()->to('/user/profile')->with('success', 'Post excluído com sucesso!');
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

    private function removeEmojis(string $text): string
    {
        // Remove emojis usando regex Unicode
        return preg_replace('/[\x{1F600}-\x{1F6FF}|\x{1F300}-\x{1F5FF}|\x{1F1E0}-\x{1F1FF}|\x{2600}-\x{26FF}|\x{2700}-\x{27BF}]/u', '', $text);
    }
}
