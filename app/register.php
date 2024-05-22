<?php
    require 'initialize.php';

    if ($request->isPost) {
        $user = new User($request, $db);
        $user->load($request->post());
        if ($user->validateRegister()) {
            // Отобразить ошибки
        } else {
            if ($user->save()) {
                header('Location: /app');
                exit;
            }
        }
    }    

    $menu_html = $menu->get_menu();

    include '../register.php';