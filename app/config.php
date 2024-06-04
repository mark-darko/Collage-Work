<?php
$menu_array = [
    ["title" => "Главная", "link" => "/app/index.php"],
    ["title" => "Блоги", "link" => "/app/posts.php"],
    ["title" => "Пользователи", "link" => "/app/users.php"],
    ["title" => "О нас", "link" => "/app/about.php"],
    ["title" => "Вход", "link" => "/app/login.php"],
    ["title" => "Регистрация", "link" => "/app/register.php"],
    ["title" => "Выход", "link" => "/app/logout.php"],
];

$dbConfig = [
    'host' => '31.31.198.99',
    'username' => 'u1947760_darko',
    'password' => 'jV1kY2zD0meX9uQ8',
    'database' => 'u1947760_ucheb',
    'port' => 3306,
];

$permissions = [
    'isGuest' => [
        '/app/users.php',
        '/app/temp-block.php',
    ],
    'isBlocked' => [
        '/app/post-action.php',
        '/app/temp-block.php',
        '/app/users.php',
    ]
];