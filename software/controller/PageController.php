<?php

if($user->sessionExists($_COOKIE['session_token']) || $user->getDataBySession($_COOKIE['session_token'], 'session_token')) {

    /*
     * set static values
     */

    $username = $user->getDataBySession($_COOKIE['session_token'], 'username');
    $firtname = $user->getDataBySession($_COOKIE['session_token'], 'firstname');
    $lastname = $user->getDataBySession($_COOKIE['session_token'], 'lastname');
    $userid = $user->getDataBySession($_COOKIE['session_token'], 'id');
    $email = $user->getDataBySession($_COOKIE['session_token'], 'email');
    $amount = $user->getDataBySession($_COOKIE['session_token'], 'amount');
    $support_pin = $user->getDataBySession($_COOKIE['session_token'], 'support_pin');
    $phone_pin = $user->getDataBySession($_COOKIE['session_token'], 'phone_pin');
    $ticket_max = $user->getDataBySession($_COOKIE['session_token'], 'ticket_max');
    $user_addr = $user->getDataBySession($_COOKIE['session_token'], 'user_addr');

    if(is_null($user_addr)) {
        $SQL = $db->prepare("UPDATE `customers` SET `user_addr` = :user_addr WHERE `id` = :id");
        $SQL->execute(array(":user_addr" => $user->getIP(), ":id" => $userid));

        $user_addr = $user->getIP();
    }

    if($user->getIP() != $user_addr) {

        // check if old session token -> team login back
        if(isset($_COOKIE['old_session_token'])) {
            // check if user is team
            if($user->isInTeam($_COOKIE['old_session_token'])) {
                // nothing to do
            } else {
                $_SESSION['info_msg'] = 'Deine Sitzung ist abgelaufen.';
                setcookie('session_token', null, time(), '/', env('COOKIE_DOMAIN'));
                header('Location: ' . env('URL') . 'auth/login/');
                die();
            }
        } else {
            $_SESSION['info_msg'] = 'Deine Sitzung ist abgelaufen.';
            setcookie('session_token', null, time(), '/', env('COOKIE_DOMAIN'));
            header('Location: ' . env('URL') . 'auth/login/');
            die();
        }
    }

    // check if user has his account activated
    if($user->getState($email) == 'pending' && $currPage != 'customer_Kundenkonto bestätigen') {
        $_SESSION['info_msg'] = 'Du musst vorher dein Kundenkonto per E-Mail bestätigen.';
        header('Location: ' . env('URL') . 'auth/logout/');
        die();
    }
}

// check if user logged in
if(strpos($currPage,'_auth') !== false) {
    if($user->sessionExists($_COOKIE['session_token'])){
        die(header('Location: '.env('URL') . 'index/'));
    }
}

if (strpos($currPage, 'customer_') !== false || strpos($currPage, 'team_') !== false || strpos($currPage, 'admin_') !== false) {

    if(!($user->loggedIn($_COOKIE['session_token']))){
        die(header('Location: ' . env('URL') . 'auth/login/'));
    }

    // check if user is on partner page and is partner
    if(strpos($currPage, 'partner_') !== false) {
        if(!$user->isPartner($_COOKIE['session_token'])) {
            die(
            header('Location: ' . env('URL') . 'selection/')
            );
        }
    }

    // check if user is on first level page and first level
    if(strpos($currPage, 'first_') !== false) {
        if(!$user->isFirst($_COOKIE['session_token'])) {
            die(
            header('Location: ' . env('URL'). 'index/')
            );
        }
    }

    // check if user is on second level page and is second level
    if(strpos($currPage, 'second_') !== false) {
        if(!$user->isSecond($_COOKIE['session_token'])) {
            die(
            header('Location: ' . env('URL'). 'index/')
            );
        }
    }

    // check if user is on third level page and is third level
    if(strpos($currPage, 'third_') !== false) {
        if(!$user->isThird($_COOKIE['session_token'])) {
            die(
            header('Location: ' . env('URL'). 'index/')
            );
        }
    }

    // check if user is on team page and is in team
    if(strpos($currPage, 'team_') !== false) {
        if(!$user->isInTeam($_COOKIE['session_token'])) {
            die(
            header('Location: ' . env('URL'). 'index/')
            );
        }
    }

    // check if user is on admin page and admin
    if(strpos($currPage, 'admin_') !== false) {
        if(!$user->isAdmin($_COOKIE['session_token'])) {
            die(
            header('Location: ' . env('URL'). 'index/')
            );
        }
    }
}

$currPageName = explode('_', $currPage) [1];
if(strpos($currPage, 'system_') !== false) {} else {
    include BASE_PATH . 'resources/additional/head.php';

    if ($user->sessionExists($_COOKIE['session_token'])) {

        if (strpos($currPage,'_hidelayout') !== false) {} else {
            if (strpos($currPage,'_hidehead') !== false) {} else {
                include BASE_PATH . 'resources/additional/header_mobile.php';
                echo '<div class="d-flex flex-column flex-root"> <div class="page d-flex flex-row flex-column-fluid">';
                include BASE_PATH . 'resources/additional/a_side.php';
                include BASE_PATH . 'resources/additional/header.php';
            }
        }
    } else {
        if (strpos($currPage,'_auth') !== false) {} else {
            include BASE_PATH . 'resources/additional/header_mobile.php';
            echo '<div class="d-flex flex-column flex-root"> <div class="page d-flex flex-row flex-column-fluid">';
            include BASE_PATH . 'resources/additional/FRONT/a_side.php';
            include BASE_PATH . 'resources/additional/header.php';
        }
    }

    include BASE_PATH . 'software/notify/alerts/toastr/sendAlert.php';
    include BASE_PATH . 'software/notify/alerts/sweet2/sendAlert.php';



    /*
     * cookie manage -> alerts
     */

    if(isset($_SESSION['error_msg']) && !empty($_SESSION['error_msg'])) {
        echo sendError($_SESSION['error_msg']);
        $_SESSION['error_msg'] = '';
        unset($_SESSION['error_msg']);
    }

    if(isset($_SESSION['info_msg']) && !empty($_SESSION['info_msg'])) {
        echo sendInfo($_SESSION['info_msg']);
        $_SESSION['info_msg'] = '';
        unset($_SESSION['info_msg']);
    }

    if(isset($_SESSION['warning_msg']) && !empty($_SESSION['warning_msg'])) {
        echo sendWarning($_SESSION['warning_msg']);
        $_SESSION['warning_msg'] = '';
        unset($_SESSION['warning_msg']);
    }

    if(isset($_SESSION['success_msg']) && !empty($_SESSION['success_msg'])) {
        echo sendSuccess($_SESSION['success_msg']);
        $_SESSION['success_msg'] = '';
        unset($_SESSION['success_msg']);
    }
}