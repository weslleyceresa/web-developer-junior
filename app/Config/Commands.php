<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class Commands extends BaseConfig
{
    /**
     * List of command classes to be added to the CLI.
     * Format: 'command-name' => FullyQualifiedClassName::class,
     */
    public $commands = [
        'db:createTables' => \App\Commands\CreateTables::class,
    ];
}
