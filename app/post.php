<?php
    require 'initialize.php';

    //$menu_html = $menu->get_menu();
    $post = new Post($user);

    if (!$request->get('id') || !$post->findOne($request->get('id'))) {
        $response = new Response($user);
        $response->redirect('/app/index.php');
    }

    if ($request->isPost) {
        if ($request->get('answer_id')) {
            $post->createComment($request->post('content'), $request->get('answer_id'));
            $response->redirect('/app/post.php', ['id' => $post->id]);
        } else {
            $post->createComment($request->post('content'));
            $response->redirect('/app/post.php', ['id' => $post->id]);
        }
    }

    if ($request->isGet) {
        if ($request->get('comment_id') && $request->get('action') == 'delete') {
            $commentObject = new Comment($user);
            $commentObject->getComment($post_id, $request->get('comment_id'));
            $commentObject->deleteComment();
            $response->redirect('/app/post.php', ['id' => $post->id]);
        } elseif ($request->get('answer_id')) {
            $commentObject = new Comment($user);
            $commentObject->getComment($post_id, $request->get('answer_id'));
            $comments = [$commentObject];
        } else 
            $comments = $post->getComments();
    }

    include 'html/post.php';