<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use Config\Database;
use App\Models\UserModel;
use App\Models\PostModel;

class UpdateTables extends BaseCommand
{
    protected $group       = 'Database';
    protected $name        = 'db:updateTables';
    protected $description = 'Atualiza tabelas existentes com base nos campos definidos nas Models.';

    public function run(array $params)
    {
        $db = Database::connect();
        $forge = Database::forge();

        $models = [
            UserModel::class,
            PostModel::class,
        ];

        foreach ($models as $modelClass) {
            $model = new $modelClass();
            $table = $model->table;

            CLI::write("Verificando tabela: $table", 'yellow');

            if (!$db->tableExists($table)) {
                CLI::error("Tabela '$table' não existe.");
                continue;
            }

            // Campos existentes no banco
            $fieldsInDb = array_map(fn($f) => $f->name, $db->getFieldData($table));

            // Campos declarados na model
            $fieldsInModel = $model->allowedFields;

            foreach ($fieldsInModel as $field) {
                if (!in_array($field, $fieldsInDb)) {
                    // Campo novo: adiciona com tipo genérico (padrão: VARCHAR 255, nullável)
                    CLI::write("Adicionando campo '$field' à tabela '$table'...", 'blue');

                    $forge->addColumn($table, [
                        $field => [
                            'type'       => 'VARCHAR',
                            'constraint' => 255,
                            'null'       => true,
                        ],
                    ]);

                    CLI::write("Campo '$field' adicionado com sucesso.", 'green');
                }
            }

            CLI::write("Tabela '$table' atualizada com base na model.", 'light_gray');
        }
    }
}
