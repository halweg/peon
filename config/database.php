<?php
//零時配置文件
return [
    'default' => 'mysql',
    'mysql' => [
        'type' => 'mysql',
        'host' => '127.0.0.1',
        'port' => '3306',
        'database' => 'demo',
        'username' => 'root',
        'password' => '123456',
    ],
    'sqlite' => [
        'type' => 'sqlite',
        'path' => __DIR__ . '/../database/database.sqlite',
    ],
];