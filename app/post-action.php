<?php
    require 'initialize.php';

    //$menu_html = $menu->get_menu();

    $post = new Post($user);

    if ($request->isPost && $user->id && !$user->isBlocked) {
        if ($request->get('id'))
            $post->load(array_merge($request->post(), ['id' => $request->get('id')]));
        else
            $post->load($request->post());

        //if ($post->validate()) { доделать validatedata
            $post->save();
            $response = new Response($user);
            if ($post->id)
                $response->redirect('/app/post.php', ['id' => $post->id]);
            else
                $response->redirect('/app/post.php', ['id' => $db->insert_id]);
        //}
    }

    if ($request->isGet && $request->get('id')) {
        $post->findOne($request->get('id')); //просто загрузка поста

        if ($request->get('action') == 'delete') {
            $post->deletePost();
            $response->redirect('/app/posts.php');
        }
    }

    include 'html/post-action.php';