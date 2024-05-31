<?php
    require 'autoload.php';
    require 'config.php';

    $request = new Request;
    $db = new Mysql($dbConfig);
    $user = new User($request, $db);
    $response = new Response($user);
    $menu = new Menu($menu_array, parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH), $response, $user);
    $post = new Post($user);

    $menu_html = $menu->get_menu();