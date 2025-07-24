<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use Config\Database;

class CreateTables extends BaseCommand
{
    protected $group       = 'Database';
    protected $name        = 'db:createTables';
    protected $description = 'Cria as tabelas users e posts se não existirem.';

    public function run(array $params)
    {
        $db = Database::connect();
        $forge = Database::forge();

        $tables = [
            'users' => function () use ($forge) {
                $forge->addField([
                    'id' => [
                        'type'           => 'INT',
                        'constraint'     => 11,
                        'unsigned'       => true,
                        'auto_increment' => true,
                    ],
                    'name' => [
                        'type'       => 'VARCHAR',
                        'constraint' => 150,
                    ],
                    'username' => [
                        'type'       => 'VARCHAR',
                        'constraint' => 100,
                        'unique'     => true,
                    ],
                    'email' => [
                        'type'       => 'VARCHAR',
                        'constraint' => 150,
                        'unique'     => true,
                    ],
                    'phone' => [
                        'type'       => 'VARCHAR',
                        'constraint' => 20,
                        'null'       => true,
                    ],
                    'password_hash' => [
                        'type'       => 'VARCHAR',
                        'constraint' => 255,
                    ],
                    'role' => [
                        'type'       => 'VARCHAR',
                        'constraint' => 50,
                        'default'    => 'user',
                    ],
                    'status' => [
                        'type'       => 'VARCHAR',
                        'constraint' => 20,
                        'default'    => 'active',
                    ],
                    'last_login' => [
                        'type' => 'DATETIME',
                        'null' => true,
                    ],
                    'created_at' => [
                        'type' => 'DATETIME',
                        'null' => true,
                    ],
                    'updated_at' => [
                        'type' => 'DATETIME',
                        'null' => true,
                    ],
                ]);
                $forge->addKey('id', true);
                $forge->createTable('users');
            },

            'posts' => function () use ($forge) {
                $forge->addField([
                    'id' => [
                        'type'           => 'INT',
                        'constraint'     => 11,
                        'unsigned'       => true,
                        'auto_increment' => true,
                    ],
                    'title' => [
                        'type'       => 'VARCHAR',
                        'constraint' => 255,
                    ],
                    'slug' => [
                        'type'       => 'VARCHAR',
                        'constraint' => 255,
                        'unique'     => true,
                    ],
                    'image_path' => [
                        'type'       => 'VARCHAR',
                        'constraint' => 255,
                        'null'       => true,
                    ],
                    'html_content' => [
                        'type' => 'TEXT',
                    ],
                    'status' => [
                        'type'       => 'VARCHAR',
                        'constraint' => 20,
                        'default'    => 'draft',
                    ],
                    'created_at' => [
                        'type' => 'DATETIME',
                        'null' => true,
                    ],
                    'updated_at' => [
                        'type' => 'DATETIME',
                        'null' => true,
                    ],
                ]);
                $forge->addKey('id', true);
                $forge->createTable('posts');
            },
        ];

        foreach ($tables as $name => $createCallback) {
            if (!$db->tableExists($name)) {
                CLI::write("Tabela \"$name\" não existe. Criando...", 'yellow');
                $createCallback();
                CLI::write("Tabela \"$name\" criada com sucesso!", 'green');
            } else {
                CLI::write("Tabela \"$name\" já existe.", 'green');
            }
        }
    }
}
