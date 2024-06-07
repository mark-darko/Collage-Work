<?php
    require 'initialize.php';

    if ($request->isPost) {
        $user = new User($request, $db);
        $user->load($request->post());
        
        var_dump(array($_POST, $_FILES));

        $user->validateRegister();
        
        if ($user->validateData() && $user->save()) {
            $response = new Response($user);
            $response->redirect('/app/index.php');
            exit;
        }
    }

    //$menu_html = $menu->get_menu();

    include 'html/register.php';