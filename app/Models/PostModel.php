<?php

namespace App\Models;

use CodeIgniter\Model;

class PostModel extends Model
{
    protected $table      = 'posts';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'title',
        'slug',
        'image_path',
        'html_content',
        'created_at',
        'updated_at'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $returnType = 'array';

    protected $validationRules = [
        'title'        => 'required|min_length[3]|max_length[255]',
        'slug'         => 'required|is_unique[posts.slug,id,{id}]', // Permite atualizar sem erro no slug próprio
        'html_content' => 'required|min_length[20]',
        'image_path'   => 'permit_empty|max_length[255]'
    ];

    protected $validationMessages = [
        'title' => [
            'required' => 'O título é obrigatório.',
        ],
        'slug' => [
            'required' => 'O slug é obrigatório.',
            'is_unique' => 'Este slug já está em uso.'
        ],
        'html_content' => [
            'required' => 'O conteúdo é obrigatório.',
        ],
        'image_path' => [
            'max_length' => 'O caminho da imagem deve ter no máximo 255 caracteres.'
        ],
    ];

    protected $beforeInsert = ['generateSlug'];
    protected $beforeUpdate = ['generateSlug'];

    protected function generateSlug(array $data)
    {
        if (isset($data['data']['title'])) {
            // Gera slug a partir do título, se slug não for enviado ou estiver vazio
            if (empty($data['data']['slug'])) {
                $data['data']['slug'] = url_title($data['data']['title'], '-', true);
            }
        }
        return $data;
    }
}
