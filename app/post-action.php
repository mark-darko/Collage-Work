<?php
    require 'initialize.php';

    //$menu_html = $menu->get_menu();

    $post = new Post($user);

    if ($request->isPost) {
        if (($user->id && $user->isAdmin)) {
            $post->load($request->post());

            //if ($post->validate()) { доделать validatedata
                $post->save();
                $response = new Response($user);
                if ($post->id)
                    $response->redirect('/app/post.php', ['id' => $post->id]);
                else
                    $response->redirect('/app/post.php', ['id' => $db->insert_id]);
                exit;
            //}
        }
    }

    if ($request->isGet && $request->get('id'))
        $post->findOne($request->get('id'));

    include 'html/post-action.php';