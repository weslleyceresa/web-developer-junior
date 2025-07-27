<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\PostModel;
use CodeIgniter\Exceptions\PageNotFoundException;
use Config\Services;

class UserController extends BaseController
{
    private const DEFAULT_AVATAR = 'uploads/avatars/default-avatar.png';
    private const AVATAR_UPLOAD_PATH = FCPATH . 'uploads/avatars';

    /**
     * Exibe o formulário de registro
     * 
     * @return string
     */
    public function registerForm()
    {
        return view('auth/register');
    }

    /**
     * Processa o registro do usuário
     * 
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function register()
    {
        $validation = $this->validateRegistration();
        if (!$validation['is_valid']) {
            return redirect()->back()->withInput()->with('errors', $validation['errors']);
        }

        $userModel = new UserModel();
        $userData = $this->buildUserData($this->request->getPost());

        if (!$userModel->insert($userData)) {
            return redirect()->back()->withInput()->with('errors', $userModel->errors());
        }

        return redirect()->to('/login')->with('success', 'Cadastro realizado com sucesso. Faça login!');
    }

    /**
     * Valida os dados de registro
     * 
     * @return array
     */
    private function validateRegistration(): array
    {
        $validation = Services::validation();
        $data = $this->request->getPost();

        $validation->setRules([
            'name'     => 'required|min_length[3]',
            'username' => 'required|min_length[3]|is_unique[users.username]',
            'email'    => 'required|valid_email|is_unique[users.email]',
            'phone'    => 'permit_empty|min_length[8]',
            'password' => 'required|min_length[6]',
        ]);

        return [
            'is_valid' => $validation->run($data),
            'errors'   => $validation->getErrors()
        ];
    }

    /**
     * Constrói os dados do usuário para registro
     * 
     * @param array $postData
     * @return array
     */
    private function buildUserData(array $postData): array
    {
        return [
            'name'          => $postData['name'],
            'username'      => $postData['username'],
            'email'         => $postData['email'],
            'phone'         => $postData['phone'] ?? null,
            'password_hash' => password_hash($postData['password'], PASSWORD_BCRYPT),
            'role'          => 'user',
            'status'        => 'active',
            'created_at'    => date('Y-m-d H:i:s'),
            'avatar_path'   => self::DEFAULT_AVATAR
        ];
    }

    /**
     * Exibe o perfil do usuário logado
     * 
     * @return string
     */
    public function profile()
    {
        $userId = session()->get('user_id');
        $userModel = new UserModel();
        $postModel = new PostModel();

        $user = $userModel->find($userId);
        $posts = $postModel->where('author_id', $userId)
                           ->orderBy('created_at', 'DESC')
                           ->findAll();

        return view('user/profile', compact('user', 'posts'));
    }

    /**
     * Exibe o formulário de edição de perfil
     * 
     * @return string
     */
    public function edit()
    {
        $userModel = new UserModel();
        $user = $userModel->find(session()->get('user_id'));
        
        return view('user/edit_profile', compact('user'));
    }

    /**
     * Atualiza o perfil do usuário
     * 
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function update()
    {
        $userId = session()->get('user_id');
        $userModel = new UserModel();
        $currentUser = $userModel->find($userId);

        $validation = $this->validateProfileUpdate($userId);
        if (!$validation['is_valid']) {
            return redirect()->back()->withInput()->with('errors', $validation['errors']);
        }

        $data = $this->request->getPost();
        $data['avatar_path'] = $this->handleAvatarUpload($currentUser);

        if (!$userModel->update($userId, $data)) {
            return redirect()->back()->withInput()->with('errors', $userModel->errors());
        }

        return redirect()->to('/user/profile')->with('success', 'Perfil atualizado com sucesso!');
    }

    /**
     * Valida os dados de atualização do perfil
     * 
     * @param int $userId
     * @return array
     */
    private function validateProfileUpdate(int $userId): array
    {
        $validation = Services::validation();
        $data = $this->request->getPost();

        $validation->setRules([
            'name'     => 'required|min_length[3]',
            'username' => "required|min_length[3]|is_unique[users.username,id,{$userId}]",
            'email'    => "required|valid_email|is_unique[users.email,id,{$userId}]",
        ]);

        return [
            'is_valid' => $validation->run($data),
            'errors'   => $validation->getErrors()
        ];
    }

    /**
     * Processa o upload do avatar e retorna o caminho
     * 
     * @param array $currentUser
     * @return string|null
     */
    private function handleAvatarUpload(array $currentUser): ?string
    {
        $file = $this->request->getFile('avatar');
        if (!$file || !$file->isValid() || $file->hasMoved()) {
            return $currentUser['avatar_path'] ?? null;
        }

        $this->ensureUploadDirectoryExists();
        $newName = $file->getRandomName();
        
        if (!$file->move(self::AVATAR_UPLOAD_PATH, $newName)) {
            log_message('error', 'Falha ao mover avatar: ' . $file->getErrorString());
            return $currentUser['avatar_path'] ?? null;
        }

        $this->removeOldAvatar($currentUser);
        return 'uploads/avatars/' . $newName;
    }

    /**
     * Cria o diretório de uploads se necessário
     */
    private function ensureUploadDirectoryExists(): void
    {
        if (!is_dir(self::AVATAR_UPLOAD_PATH)) {
            mkdir(self::AVATAR_UPLOAD_PATH, 0777, true);
            file_put_contents(self::AVATAR_UPLOAD_PATH . '/index.html', '');
        }
    }

    /**
     * Remove o avatar antigo do usuário
     * 
     * @param array $user
     */
    private function removeOldAvatar(array $user): void
    {
        if (!empty($user['avatar_path']) && $user['avatar_path'] !== self::DEFAULT_AVATAR) {
            $oldPath = FCPATH . $user['avatar_path'];
            if (file_exists($oldPath)) {
                unlink($oldPath);
            }
        }
    }

    /**
     * Exibe perfil público de um usuário
     * 
     * @param string $username
     * @return string
     * @throws PageNotFoundException
     */
    public function publicProfile(string $username)
    {
        $userModel = new UserModel();
        $user = $userModel->where('username', $username)->first();
        
        if (!$user) {
            throw PageNotFoundException::forPageNotFound("Usuário não encontrado");
        }

        $postModel = new PostModel();
        $posts = $postModel->where('author_id', $user['id'])
                           ->where('status', 'published')
                           ->orderBy('created_at', 'DESC')
                           ->findAll();

        return view('user/public_profile', compact('user', 'posts'));
    }
}