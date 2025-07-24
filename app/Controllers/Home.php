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

        if ($userId) {
            $userModel = new UserModel();
            $user = $userModel->find($userId);

            if ($user && $user['role'] === 'admin') {
                return redirect()->to('/admin/posts');
            } else {
                return redirect()->to('/blog');
            }
        }

        return view('home');
    }
}
