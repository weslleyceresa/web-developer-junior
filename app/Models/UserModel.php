<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table      = 'users';
    protected $primaryKey = 'id';

    // Campos que podem ser atribuídos em massa
    protected $allowedFields = [
        'name',            // Nome completo do usuário (pode repetir)
        'username',        // Nome de usuário único para login
        'email',           // E-mail único
        'phone',           // Telefone (opcional)
        'password_hash',   // Senha criptografada (bcrypt)
        'created_at',      // Timestamp de criação (auto)
        'updated_at',      // Timestamp de atualização (auto)
        'last_login',      // Último login
        'status',          // active, inactive, banned
        'role'             // admin, editor, user
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $returnType = 'array';

    // Regras de validação
    protected $validationRules = [
        'name'          => 'required|min_length[3]|max_length[150]',
        'username'      => 'required|min_length[3]|max_length[100]|is_unique[users.username,id,{id}]',
        'email'         => 'required|valid_email|is_unique[users.email,id,{id}]',
        'password_hash' => 'required|min_length[60]',
        'role'          => 'permit_empty|in_list[admin,editor,user]',
        'status'        => 'permit_empty|in_list[active,inactive,banned]',
    ];

    protected $validationMessages = [
        'username' => [
            'is_unique' => 'Este nome de usuário já está em uso.'
        ],
        'email' => [
            'is_unique' => 'Este e-mail já está em uso.'
        ],
        'name' => [
            'required' => 'O nome é obrigatório.',
        ]
    ];
}
