<?php

namespace App\Controllers;

use App\Models\UserModel;

class UserController extends BaseController
{
    /**
     * Exibe o formulário de cadastro.
     */
    public function registerForm()
    {
        return view('auth/register');
    }

    /**
     * Processa o cadastro de um novo usuário.
     */
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
            'role'          => 'user', // padrão
            'status'        => 'active',
            'created_at'    => date('Y-m-d H:i:s'),
        ];

        if (!$userModel->insert($userData)) {
            return redirect()->back()->withInput()->with('errors', $userModel->errors());
        }

        return redirect()->to('/login')->with('success', 'Cadastro realizado com sucesso. Faça login!');
    }
}
