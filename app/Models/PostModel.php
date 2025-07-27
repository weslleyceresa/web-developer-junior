<?php

namespace App\Models;

use CodeIgniter\Model;

class PostModel extends Model
{
    protected $table      = 'posts';
    protected $primaryKey = 'id';

    // Campos permitidos para escrita
    protected $allowedFields = [
        'title',         // Título do post
        'slug',          // URL amigável
        'image_path',    // Caminho da imagem (opcional)
        'html_content',  // Conteúdo HTML completo
        'excerpt',       // Resumo opcional do post
        'author_id',     // ID do autor (usuário)
        'status',        // Status do post (draft, published, archived)
        'created_at',    // Data de criação (automático)
        'updated_at'     // Data de atualização (automático)
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $returnType = 'array';

    // Regras de validação
    protected $validationRules = [
        'title'        => 'required|min_length[3]|max_length[255]',
        'slug'         => 'required|is_unique[posts.slug,id,{id}]',
        'html_content' => 'required|min_length[20]',
        'image_path'   => 'permit_empty|max_length[255]',
        'status'       => 'permit_empty|in_list[draft,published,archived]',
        'author_id'    => 'required|integer',
    ];

    protected $validationMessages = [
        'title' => [
            'required' => 'O título é obrigatório.',
        ],
        'slug' => [
            'required'   => 'O slug é obrigatório.',
            'is_unique'  => 'Este slug já está em uso.',
        ],
        'html_content' => [
            'required' => 'O conteúdo é obrigatório.',
        ],
        'image_path' => [
            'max_length' => 'O caminho da imagem deve ter no máximo 255 caracteres.',
        ]
    ];


    public function getPaginatedWithAuthors(int $limit, int $offset = 0)
    {
        return $this->select('posts.*, users.name as author_name, users.username as author_username, users.avatar_path')
            ->join('users', 'users.id = posts.author_id')
            ->where('posts.status', 'published')
            ->orderBy('posts.created_at', 'DESC')
            ->findAll($limit, $offset);
    }

    public function searchPosts(string $query)
    {
        return $this->select('posts.*, users.name as author_name, users.username as author_username, users.avatar_path')
            ->join('users', 'users.id = posts.author_id')
            ->where('posts.status', 'published')
            ->groupStart()
            ->like('posts.title', $query)
            ->orLike('posts.html_content', $query)
            ->orLike('users.name', $query)
            ->groupEnd()
            ->orderBy('posts.created_at', 'DESC')
            ->findAll();
    }
}
