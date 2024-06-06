<?php
    require 'initialize.php';

    if ($request->isPost) {
        $user = new User($request, $db);
        $user->load($request->post());
        
        $user->validateLogin();

        if ($user->validateData() && $user->login()) {
            $response = new Response($user);
            $response->redirect('/app/index.php');
            exit;
        }
    }

    //$menu_html = $menu->get_menu();

    include 'html/login.php';