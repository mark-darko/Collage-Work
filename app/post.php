<?php
    require 'initialize.php';

    //$menu_html = $menu->get_menu();
    $post = new Post($user);

    if (!$request->get('id') || !$post->findOne($request->get('id'))) {
        $response = new Response($user);
        $response->redirect('/app/index.php');
        exit;
    } else {
        $author = new User($request, $db);
        $author->identity($post->user_id);
    }

    include '../post.php';