<?php
    require 'initialize.php';

    if (!$request->get('user_id'))
        $response->redirect('/app/index.php');

    $selectedUser = new User($request, $db);
    $selectedUser->identity($request->get('user_id'));

    if ($request->isPost && $request->post('date_block')) {
        $selectedUser->blockUser($request->post('date_block'));
        $response->redirect('/app/users.php');
    }

    if ($request->isGet) {
        if ($request->get('action') == 'unblock') {
            $selectedUser->unblockUser();
            $response->redirect('/app/users.php');
        }

        if ($request->get('action') == 'permanentBlock') {
            $selectedUser->blockUser();
            $response->redirect('/app/users.php');
        }
    }

    include 'html/temp-block.php';