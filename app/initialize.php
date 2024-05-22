<?php
    require 'autoload.php';
    require 'config.php';

    $menu = new Menu($menu_array, $_SERVER['REQUEST_URI']);
    $request = new Request;
    $db = new Mysql($dbConfig);