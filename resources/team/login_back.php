<?php
if($user->isInTeam($_COOKIE['old_session_token'])){
    $SQL = $db->prepare("SELECT * FROM `customers` WHERE `session_token` = :session_token");
    $SQL->execute(array(":session_token" => $_COOKIE['session_token']));
    $data = $SQL->fetch(PDO::FETCH_ASSOC);

    setcookie('session_token', $_COOKIE['old_session_token'],time()+864000,'/', env('COOKIE_DOMAIN'));
    unset($_COOKIE['old_session_token']);
    setcookie('old_session_token', '', time() - 3600,'/', env('COOKIE_DOMAIN'));
    die(header('Location: '.$helper->url().'team/user/'.str_replace('=','',base64_encode($data['support_pin']))));
} else {
    die(header('Location: '.$helper->url()));
    unset($_COOKIE['old_session_token']);
    setcookie('old_session_token', '', time() - 3600,'/', env('COOKIE_DOMAIN'));
}