<?php

if(isset($_POST['sendRegister'])) {

    $error = null;

    if(empty($error)) {

        $userinfo = $_POST['email'];

        $SQL = $db->prepare("SELECT * FROM `customers` WHERE `username` = :username OR `email` = :useremail");
        $SQL->execute(array(":username" => $userinfo, ":useremail" => $userinfo));
        $userInfo = $SQL->fetch(PDO::FETCH_ASSOC);
        if($SQL->rowCount() == 1) {

            $verify_code = $helper->generateRandomString(12);

            $SQL = $db->prepare("UPDATE `customers` SET `verify_code` = :verify_code WHERE `id` = :user_id");
            $SQL->execute(array(":verify_code" => $verify_code, ":user_id" => $userInfo['id']));

            $body = file_get_contents(BASE_PATH . 'public/email_templates/auth/register_confirm.html');
            $body = str_replace('%username%', $userInfo['username'], $body);
            $body = str_replace('%confirm_url%', env('URL') . 'auth/register/confirm/' . $verify_code . '/', $body);
            $body = str_replace('%project_name%', env('EMAIL_PROJECT_NAME'), $body);
            sendMail($userInfo['email'], $userInfo['username'], $body, 'Kundenkonto aktivieren');

            echo sendSuccess('E-Mail wurde gesendet.');
            header('refresh: 0.5');
        } else {
            echo sendError('Keine passenden Daten zu deiner Eingabe gefunden.');
        }

    } else {
        echo sendError($error);
    }
}