<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table      = 'users';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'username',
        'password_hash',
        'created_at'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    // Como não usamos updated_at, deixamos $updatedField vazio e $useTimestamps true só para created_at

    protected $returnType = 'array';

    protected $validationRules = [
        'username'      => 'required|min_length[3]|max_length[100]|is_unique[users.username,id,{id}]',
        'password_hash' => 'required|min_length[60]', // hash bcrypt tem 60 caracteres
    ];

    protected $validationMessages = [
        'username' => [
            'is_unique' => 'Este nome de usuário já está em uso.'
        ]
    ];
}
