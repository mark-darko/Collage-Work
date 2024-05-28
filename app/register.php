<?php
    require 'initialize.php';

    if ($request->isPost) {
        $user = new User($request, $db);
        $user->load($request->post());
        
        if ($user->validateRegister()) {
            if ($user->save()) {
                $response = new Response($user);
                $response->redirect('/app/index.php');
                exit;
            }
        }
    }

    //$menu_html = $menu->get_menu();

    include '../register.php';