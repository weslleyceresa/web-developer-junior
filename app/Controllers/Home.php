<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\UserModel;

class Home extends Controller
{
    public function index()
    {
        $session = session();
        $userId = $session->get('user_id');

        // Se a sessão estiver ativa e o usuário existir
        if ($userId) {
            $userModel = new UserModel();
            $user = $userModel->find($userId);

            if ($user) {
                // Redirecionamento baseado no papel do usuário
                if ($user['role'] === 'admin') {
                    return redirect()->to('/admin/posts');
                } else {
                    return redirect()->to('/blog');
                }
            } else {
                // Sessão inválida (user_id sem usuário correspondente) – limpa a sessão
                $session->destroy();
            }
        }

        // Usuário não logado ou sessão inválida
        return view('home');
    }
}
