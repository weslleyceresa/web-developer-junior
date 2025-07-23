<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use Config\Database;

class CreateTables extends BaseCommand
{
    protected $group       = 'Database';
    protected $name        = 'db:createTables';
    protected $description = 'Cria as tabelas users e posts se não existirem';

    public function run(array $params)
    {
        $db = Database::connect();

        // users
        if (!$db->tableExists('users')) {
            CLI::write('Tabela "users" não existe. Criando...', 'yellow');

            $forge = Database::forge();

            $forge->addField([
                'id' => [
                    'type'           => 'INT',
                    'constraint'     => 11,
                    'unsigned'       => true,
                    'auto_increment' => true,
                ],
                'username' => [
                    'type'       => 'VARCHAR',
                    'constraint' => '100',
                    'unique'     => true,
                ],
                'password_hash' => [
                    'type'       => 'VARCHAR',
                    'constraint' => '255',
                ],
                'created_at' => [
                    'type' => 'DATETIME',
                    'null' => true,
                ],
            ]);
            $forge->addKey('id', true);
            $forge->createTable('users');
            CLI::write('Tabela "users" criada com sucesso!', 'green');
        } else {
            CLI::write('Tabela "users" já existe.', 'green');
        }

        // posts
        if (!$db->tableExists('posts')) {
            CLI::write('Tabela "posts" não existe. Criando...', 'yellow');

            $forge = Database::forge();

            $forge->addField([
                'id' => [
                    'type'           => 'INT',
                    'constraint'     => 11,
                    'unsigned'       => true,
                    'auto_increment' => true,
                ],
                'title' => [
                    'type'       => 'VARCHAR',
                    'constraint' => '255',
                ],
                'slug' => [
                    'type'       => 'VARCHAR',
                    'constraint' => '255',
                    'unique'     => true,
                ],
                'image_path' => [
                    'type'       => 'VARCHAR',
                    'constraint' => '255',
                    'null'       => true,
                ],
                'html_content' => [
                    'type' => 'TEXT',
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
            CLI::write('Tabela "posts" criada com sucesso!', 'green');
        } else {
            CLI::write('Tabela "posts" já existe.', 'green');
        }
    }
}
