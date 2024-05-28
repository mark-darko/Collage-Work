<?php
    require 'initialize.php';

    //$menu_html = $menu->get_menu();

    $postobject = new Post($user);

    $posts = $postobject->findLatest();

    include 'html/index.php';