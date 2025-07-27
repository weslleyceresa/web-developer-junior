<?php

namespace App\Controllers;

use App\Models\PostModel;
use CodeIgniter\Exceptions\PageNotFoundException;
use Config\Services;

class PostsController extends BaseController
{
    private const DEFAULT_STATUS = 'published';
    private const REDIRECT_ADMIN = '/admin/posts';
    private const REDIRECT_PROFILE = '/user/profile';
    private const REDIRECT_BLOG = '/blog';

    protected $postModel;

    public function __construct()
    {
        $this->postModel = new PostModel();
        helper(['url', 'text']);
    }

    /**
     * Exibe a lista de posts administrativa com busca
     * 
     * @return string
     */
    public function index()
    {
        $search = $this->request->getGet('search');
        $builder = $this->buildPostQuery($search);

        return view('admin/posts_list', [
            'posts' => $builder->orderBy('posts.created_at', 'DESC')->findAll(),
            'search' => $search
        ]);
    }

    /**
     * Exibe o formulário para criar novo post
     * 
     * @return string
     */
    public function new()
    {
        return $this->renderPostForm('Novo Post', null, '/admin/posts/create');
    }

    /**
     * Processa a criação de um novo post
     * 
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function store()
    {
        $this->requireLogin();

        $postData = $this->preparePostData();

        // Trata o upload da imagem
        $image = $this->request->getFile('post_image');
        if ($image && $image->isValid() && !$image->hasMoved()) {
            $newName = $image->getRandomName();
            $uploadPath = FCPATH . 'uploads/posts';
            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0777, true);
                file_put_contents($uploadPath . '/index.html', '');
            }

            $image->move($uploadPath, $newName);
            $postData['image_path'] = 'uploads/posts/' . $newName;
        }

        $validation = $this->validatePost($postData);

        if (!$validation['is_valid']) {
            return redirect()->back()->withInput()->with('errors', $validation['errors']);
        }

        if (!$this->postModel->insert($postData)) {
            return redirect()->back()->withInput()->with('errors', $this->postModel->errors());
        }

        return redirect()->to(self::REDIRECT_BLOG)->with('success', 'Post criado com sucesso!');
    }
    /**
     * Exibe o formulário de edição de post
     * 
     * @param int $id ID do post
     * @return string
     */
    public function edit($id)
    {
        $post = $this->getPostOrFail($id);
        return $this->renderPostForm('Editar Post', $post, "/admin/posts/update/{$id}");
    }

    /**
     * Processa a atualização de um post existente
     * 
     * @param int $id ID do post
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function update($id)
    {
        $this->getPostOrFail($id); // Verifica se o post existe
        $data = $this->request->getPost();

        $validation = Services::validation();
        $validation->setRules([
            'title'        => 'required|min_length[3]',
            'html_content' => 'required|min_length[10]'
        ]);

        if (!$validation->run($data)) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        $this->postModel->update($id, [
            'title'        => $data['title'],
            'html_content' => $data['html_content'],
            'updated_at'   => date('Y-m-d H:i:s')
        ]);

        return redirect()->to(self::REDIRECT_ADMIN)->with('success', 'Post atualizado com sucesso!');
    }

    /**
     * Exclui um post (admin)
     * 
     * @param int $id ID do post
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function delete($id)
    {
        $post = $this->getPostOrFail($id);
        $this->postModel->delete($id);
        return redirect()->to(self::REDIRECT_ADMIN)->with('success', 'Post excluído com sucesso!');
    }

    /**
     * Exclui um post do perfil do usuário
     * 
     * @param int $id ID do post
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function deleteFromProfile($id)
    {
        $this->requireLogin();
        $post = $this->getPostOrFail($id);
        $this->verifyPostOwnership($post);

        $this->postModel->delete($id);
        return redirect()->to(self::REDIRECT_PROFILE)->with('success', 'Post excluído com sucesso!');
    }

    /***********************
     * MÉTODOS AUXILIARES *
     ***********************/

    /**
     * Prepara os dados para criação de um novo post
     * 
     * @return array
     */
    private function preparePostData(): array
    {
        $title = $this->request->getPost('title');
        $slugBase = url_title(convert_accented_characters($this->removeEmojis($title)), '-', true);

        return [
            'title'        => $title,
            'slug'         => $this->generateUniqueSlug($slugBase),
            'html_content' => $this->request->getPost('html_content'),
            'author_id'    => session()->get('user_id'),
            'status'       => self::DEFAULT_STATUS
        ];
    }

    /**
     * Valida os dados de um post
     * 
     * @param array $data
     * @return array
     */
    private function validatePost(array $data): array
    {
        $validation = Services::validation();
        $validation->setRules($this->postModel->getValidationRules());

        return [
            'is_valid' => $validation->run($data),
            'errors'   => $validation->getErrors()
        ];
    }

    /**
     * Constrói a query base para posts
     * 
     * @param string|null $search Termo de busca
     * @return \CodeIgniter\Database\BaseBuilder
     */
    private function buildPostQuery(?string $search = null)
    {
        $builder = $this->postModel
            ->select('posts.*, users.name AS author_name, users.username AS author_username, users.avatar_path')
            ->join('users', 'users.id = posts.author_id', 'left');

        if ($search) {
            $builder->groupStart()
                ->like('posts.title', $search)
                ->orLike('users.name', $search)
                ->groupEnd();
        }

        return $builder;
    }

    /**
     * Renderiza o formulário de post
     * 
     * @param string $title Título do formulário
     * @param array|null $post Dados do post
     * @param string $action URL de ação
     * @return string
     */
    private function renderPostForm(string $title, ?array $post, string $action): string
    {
        return view('admin/post_form', [
            'title'  => $title,
            'post'   => $post,
            'action' => $action
        ]);
    }

    /**
     * Obtém um post ou falha
     * 
     * @param int $id ID do post
     * @return array
     * @throws PageNotFoundException
     */
    private function getPostOrFail(int $id): array
    {
        $post = $this->postModel->find($id);
        if (!$post) {
            throw PageNotFoundException::forPageNotFound("Post não encontrado");
        }
        return $post;
    }

    /**
     * Verifica se o usuário é dono do post
     * 
     * @param array $post Dados do post
     * @return void
     */
    private function verifyPostOwnership(array $post): void
    {
        $userId = session()->get('user_id');
        if ($post['author_id'] !== $userId) {
            throw new \RuntimeException('Você não tem permissão para excluir este post.');
        }
    }

    /**
     * Requer que o usuário esteja logado
     * 
     * @return void
     */
    private function requireLogin(): void
    {
        if (!session()->get('user_id')) {
            throw new \RuntimeException('Acesso não autorizado');
        }
    }

    /**
     * Gera um slug único
     * 
     * @param string $slugBase Base do slug
     * @return string
     */
    private function generateUniqueSlug(string $slugBase): string
    {
        $slug = $slugBase;
        $counter = 1;

        while ($this->postModel->where('slug', $slug)->first()) {
            $slug = $slugBase . '-' . $counter++;
        }

        return $slug;
    }

    /**
     * Remove emojis do texto
     * 
     * @param string $text Texto original
     * @return string
     */
    private function removeEmojis(string $text): string
    {
        return preg_replace('/[\x{1F600}-\x{1F6FF}|\x{1F300}-\x{1F5FF}|\x{1F1E0}-\x{1F1FF}|\x{2600}-\x{26FF}|\x{2700}-\x{27BF}]/u', '', $text);
    }
}
