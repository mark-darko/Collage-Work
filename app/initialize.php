<?php
    require 'autoload.php';
    require 'config.php';

    date_default_timezone_set('Europe/Moscow');

    $request = new Request;
    $db = new Mysql($dbConfig);
    $user = new User($request, $db);
    $response = new Response($user);

    Middleware::checkPermissions($user, $response, $permissions);

    $menu = new Menu($menu_array, $response, $user);
    $post = new Post($user);

    $menu_html = $menu->get_menu();