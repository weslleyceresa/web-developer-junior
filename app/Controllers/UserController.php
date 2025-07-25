<?php

namespace App\Controllers;

use App\Models\UserModel;

class UserController extends BaseController
{
    public function registerForm()
    {
        return view('auth/register');
    }

    public function register()
    {
        $data = $this->request->getPost();
        $validation = \Config\Services::validation();

        $validation->setRules([
            'name'     => 'required|min_length[3]',
            'username' => 'required|min_length[3]|is_unique[users.username]',
            'email'    => 'required|valid_email|is_unique[users.email]',
            'phone'    => 'permit_empty|min_length[8]',
            'password' => 'required|min_length[6]',
        ]);

        if (!$validation->run($data)) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        $userModel = new UserModel();

        $userData = [
            'name'          => $data['name'],
            'username'      => $data['username'],
            'email'         => $data['email'],
            'phone'         => $data['phone'],
            'password_hash' => password_hash($data['password'], PASSWORD_BCRYPT),
            'role'          => 'user',
            'status'        => 'active',
            'created_at'    => date('Y-m-d H:i:s'),
            'avatar_path'   => 'uploads/avatars/default-avatar.png'
        ];

        if (!$userModel->insert($userData)) {
            return redirect()->back()->withInput()->with('errors', $userModel->errors());
        }
        
        // Atualiza avatar na sessão se foi modificado
        if (isset($data['avatar_path'])) {
            session()->set('avatar_path', $data['avatar_path']);
        }

        return redirect()->to('/login')->with('success', 'Cadastro realizado com sucesso. Faça login!');
    }

    public function profile()
    {
        $userId = session()->get('user_id');
        $userModel = new UserModel();
        $postModel = new \App\Models\PostModel();

        $user = $userModel->find($userId);
        $posts = $postModel->where('author_id', $userId)
            ->orderBy('created_at', 'DESC')
            ->findAll();

        return view('user/profile', ['user' => $user, 'posts' => $posts]);
    }

    public function edit()
    {
        $userId = session()->get('user_id');
        $userModel = new UserModel();
        $user = $userModel->find($userId);

        return view('user/edit_profile', ['user' => $user]);
    }

    public function update()
    {
        $userId = session()->get('user_id');
        $userModel = new UserModel();
        $currentUser = $userModel->find($userId);

        $data = $this->request->getPost();

        // Validação
        $validation = \Config\Services::validation();
        $validation->setRules([
            'name' => 'required|min_length[3]',
            'username' => "required|min_length[3]|is_unique[users.username,id,{$userId}]",
            'email' => "required|valid_email|is_unique[users.email,id,{$userId}]",
        ]);

        if (!$validation->run($data)) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        // Upload do avatar
        $file = $this->request->getFile('avatar');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $uploadPath = FCPATH . 'uploads/avatars';

            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0777, true);
                file_put_contents($uploadPath . '/index.html', '');
            }

            $newName = $file->getRandomName();
            if (!$file->move($uploadPath, $newName)) {
                log_message('error', 'Falha ao mover avatar: ' . $file->getErrorString());
            } else {
                $data['avatar_path'] = 'uploads/avatars/' . $newName;

                // Remove o avatar antigo se não for o padrão
                if (
                    !empty($currentUser['avatar_path']) &&
                    $currentUser['avatar_path'] !== 'uploads/avatars/default-avatar.png'
                ) {
                    $oldAvatarPath = FCPATH . $currentUser['avatar_path'];
                    if (file_exists($oldAvatarPath)) {
                        unlink($oldAvatarPath);
                    }
                }
            }
        }

        // Atualiza o usuário
        if (!$userModel->update($userId, $data)) {
            return redirect()->back()->withInput()->with('errors', $userModel->errors());
        }

        return redirect()->to('/user/profile')->with('success', 'Perfil atualizado com sucesso!');
    }

    public function publicProfile($username)
    {
        $userModel = new UserModel();
        $postModel = new \App\Models\PostModel();

        $user = $userModel->where('username', $username)->first();
        if (!$user) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound("Usuário não encontrado");
        }

        $posts = $postModel->where('author_id', $user['id'])
            ->where('status', 'published')
            ->orderBy('created_at', 'DESC')
            ->findAll();

        return view('user/public_profile', ['user' => $user, 'posts' => $posts]);
    }
}
