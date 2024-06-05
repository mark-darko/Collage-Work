<?php
    require 'autoload.php';
    require 'config.php';

    error_reporting(E_ALL);
ini_set('display_errors', 1);



    date_default_timezone_set('Europe/Moscow');

    $request = new Request;
    $db = new Mysql($dbConfig);
    $user = new User($request, $db);
    $response = new Response($user);

    Middleware::checkPermissions($user, $response, $permissions);

    $menu = new Menu($menu_array, $response, $user);
    $post = new Post($user);

    $menu_html = $menu->get_menu();