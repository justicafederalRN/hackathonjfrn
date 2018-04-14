<?php
$config = require __DIR__ . '/database.php';


$environments = [];

$defaultEnv = 'default';
$environments[$defaultEnv] = [
    'adapter' => str_replace('pdo_', '', $config['driver']),
    'host'    => $config['host'],
    'user'    => $config['user'],
    'pass'    => $config['password'],
    'name'    => $config['dbname']
];



$config = [
    'paths'        => [
        'migrations' => 'db/migrations',
        'seeds'      => 'db/seeds'
    ],
    'environments' => [
        'default_migration_table' => 'migrations',
        'default_database'        => $defaultEnv,
    ]
];

return array_merge_recursive($config, ['environments' => $environments]);