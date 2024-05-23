<?php
    require 'autoload.php';
    require 'config.php';

    $menu = new Menu($menu_array, parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH));
    $request = new Request;
    $db = new Mysql($dbConfig);

    $menu_html = $menu->get_menu();