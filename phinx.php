<?php

return [
    'paths' => [
        'migrations' => 'db/migrations',
        'seeds' => 'db/seeds',
    ],
    'environments' => [
        'default_migration_table' => 'phinxlog',
        'default_environment' => 'development',
        'development' => [
            'adapter' => 'pgsql',
            'host' => '127.0.0.1',
            'name' => 'amarBank_test',
            'user' => 'postgres',
            'pass' => 'postgres',
            'port' => 5432,
        ]
    ],
];