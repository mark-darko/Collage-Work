<?php
    require 'initialize.php';

    $user->logout();

    $response->redirect('/app/index.php');