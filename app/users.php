<?php
    require 'initialize.php';

    //$menu_html = $menu->get_menu();

    $users = $user->getAllUsers();

    include 'html/users.php';