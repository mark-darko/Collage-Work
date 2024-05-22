<?php
    require 'initialize.php';

    if ($request->isPost) {
        $user = new User($request, $db);
        $user->load($request->post());
        
        if (!$user->validateLogin()) {
            if ($user->login()) {
                header("Location: /app?token={$user->token}");
                exit;
            }
        }
    }

    //$menu_html = $menu->get_menu();

    include '../login.php';