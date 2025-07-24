<?php

namespace App\Controllers;

use App\Models\UserModel;

class AuthController extends BaseController
{
    /**
     * Exibe o formulário de login.
     */
    public function login()
    {
        return view('auth/login', ['title' => 'Login']);
    }

    /**
     * Processa o login do usuário.
     */
    public function doLogin()
    {
        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        $userModel = new UserModel();
        $user = $userModel->where('username', $username)->first();

        // Verifica se o usuário existe e a senha está correta
        if (!$user || !password_verify($password, $user['password_hash'])) {
            return redirect()->back()->with('error', 'Usuário ou senha inválidos');
        }

        // Atualiza último login
        $userModel->update($user['id'], ['last_login' => date('Y-m-d H:i:s')]);

        // Após autenticar e salvar dados na sessão
        session()->set([
            'user_id' => $user['id'],
            'username' => $user['username'],
            'user_name' => $user['name'],
            'role' => $user['role']
        ]);

        // Redirecionamento com base na role
        if ($user['role'] === 'admin') {
            return redirect()->to('/admin/posts');
        }

        return redirect()->to('/blog');
    }

    /**
     * Realiza logout e destrói a sessão.
     */
    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login');
    }
}
