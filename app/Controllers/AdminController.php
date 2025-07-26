<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\PostModel;

class AdminController extends BaseController
{
    public function dashboard()
    {
        $userModel = new UserModel();
        $postModel = new PostModel();

        $totalUsers = $userModel->countAll();
        $activeUsers = $userModel->where('status', 'active')->countAllResults();
        $totalPosts = $postModel->countAll();

        $recentPosts = $postModel->orderBy('created_at', 'DESC')->findAll(5);
        $recentUsers = $userModel->orderBy('created_at', 'DESC')->findAll(5);

        return view('admin/dashboard', [
            'title' => 'Dashboard Admin',
            'totalUsers' => $totalUsers,
            'activeUsers' => $activeUsers,
            'totalPosts' => $totalPosts,
            'recentPosts' => $recentPosts,
            'recentUsers' => $recentUsers,
        ]);
    }
}
