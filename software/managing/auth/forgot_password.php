<?php

// reset function

if (isset($_POST['new_password_submit'])) {
    if (isset($_POST['new_password']) && !empty($_POST['new_password'])) {
        if (isset($_POST['new_password_repeat']) && !empty($_POST['new_password_repeat'])) {
            if ($_POST['new_password'] == $_POST['new_password_repeat']) {
                if (isset($_POST['key']) && !empty($_POST['key'])) {
                    $SQLGet = $db->prepare("SELECT * FROM `customers_password_resets` WHERE `key` = :key");
                    $SQLGet->execute(array(":key" => $_POST['key']));

                    if ($SQLGet->rowCount() == 1) {
                        $SQLGet = $db->prepare("SELECT * FROM `customers_password_resets` WHERE `key` = :key");
                        $SQLGet->execute(array(":key" => $_POST['key']));
                        $userInfo = $SQLGet->fetch(PDO::FETCH_ASSOC);

                        $user->resetPW($userInfo['user_info'], $_POST['new_password']);

                        $deleteKey = $db->prepare("DELETE FROM `customers_password_resets` WHERE `key` = :key");
                        $deleteKey->execute(array(":key" => $_POST['key']));

                        echo sendSuccess('Deine Passwort-Änderung war erfolgreich.');
                        header('Location: ' . env('URL') . 'auth/login/');
                    } else {
                        echo sendError('Dein Reset-Key ist ungültig.');
                        header('Location: ' . env('URL') . 'auth/login/');
                    }
                } else {
                    echo sendError('Deine Passwörter stimmen nicht überein.');
                }
            } else {
                echo sendError('Bitte wiederhole deine Passwort-Eingabe.');
            }
        } else {
            echo sendError('Bitte gebe ein Passwort ein.');
        }
    }
}

// generate key for password reset & send request info
if(isset($_POST['password_submit'])) {
    $error = null;

    if(empty($_POST['user_info'])){
        $error = 'Bitte gib einen Benutzernamen oder eine E-Mail ein.';
    }

    if(empty($error)) {
        if(isset($_POST['user_info']) && !empty($_POST['user_info'])) {

            $username = $_POST['user_info'];

            $SQL = $db->prepare("SELECT * FROM `customers` WHERE `username` = :username OR `email` = :email");
            $SQL->execute(array(":username" => $username, ":email" => $username));
            $userInfo = $SQL->fetch(PDO::FETCH_ASSOC);

            if($SQL->rowCount() == 1) {

                function generateVerifyCode($length = 15)
                {
                    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                    $charactersLength = strlen($characters);

                    $randomString = '';

                    for ($i = 0; $i < $length; $i++) {
                        $randomString .= $characters[rand(0, $charactersLength - 1)];
                    }

                    return $randomString;
                }

                $verify_code = generateVerifyCode();

                $setKey = $db->prepare("INSERT INTO `customers_password_resets` (`user_info`, `key`) VALUES (:user_info, :verify_code)");
                $setKey->execute(array(":user_info" => $username, ":verify_code" => $verify_code));

                $body = file_get_contents(BASE_PATH . 'public/email_templates/auth/forgot_password.html');
                $body = str_replace('%username%', $userInfo['username'], $body);
                $body = str_replace('%passwort_reset_url%', env('URL') . 'auth/forgot-password/reset/' . $verify_code . '/', $body);
                $body = str_replace('%project_name%', env('EMAIL_PROJECT_NAME'), $body);
                sendMail($userInfo['email'], $userInfo['username'], $body, 'Anfrage zum Passwort zurücksetzen');

                echo sendSuccess('Wir haben dir eine E-Mail zum Passwort-Reset geschickt.');
            } else {
                echo sendError('Wir konnten keinen Account mit dem Benutzernamen / der E-Mail Adresse finden.');
            }
        } else {
            echo sendError('Bitte gebe einen Benutzernamen oder E-Mail Adresse ein.');
        }
    } else {
        echo sendError($error);
    }
}