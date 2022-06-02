<?php

$register_success = null;

if(isset($_POST['register_submit'])) {

    $error = null;

    if($helper->getSetting('register') == 0){
        $error = 'Das Accounterstellen ist derzeit deaktiviert';
    }


    /*$captcha_response = $site->validateCaptcha($_POST['h-captcha-response']);
    if($captcha_response == false){
        $error = 'Ungültige Anfrage bitte versuche es erneut (ERR-Captcha)';
    }*/

    if(empty($_POST['username'])) {
        $error = 'Bitte gebe einen Benutzernamen an.';
    }

    if(preg_match("/^[a-zA-Z0-9]+$/", $_POST['username']) == 0){
        $error = 'Dein Benutzername enthält unerlaubte Zeichen.';
    }

    if($user->exists($_POST['username'])){
        $error = 'Ein Benutzer mit diesem Benutzernamen existiert bereits.';
    }

    if(empty($_POST['email'])) {
        $error = 'Bitte gebe eine E-Mail Adresse an.';
    }

    if(filter_var($_POST['email'],FILTER_VALIDATE_EMAIL) == false){
        $error = 'Bitte gebe eine gültige E-Mail an.';
    }

    if($user->exists($_POST['email'])){
        $error = 'Ein Benutzer mit dieser E-Mail Adresse existiert bereits.';
    }

    if(empty($_POST['password'])) {
        $error = 'Bitte gebe ein Passwort an.';
    }

    if(empty($_POST['password_repeat'])) {
        $error = 'Bitte wiederhole dein Passwort.';
    }

    if(empty($_POST['agreement'])) {
        $error = 'Bitte akzeptiere unsere AGB und Datenschutzerklärung.';
    }

    if(empty($error)){
        $verify_code = $helper->generateRandomString(12);

        $body = file_get_contents(BASE_PATH . 'public/email_templates/auth/register_confirm.html');
        $body = str_replace('%username%', $_POST['username'], $body);
        $body = str_replace('%confirm_url%', env('URL') . 'auth/register/confirm/' . $verify_code . '/', $body);
        $body = str_replace('%project_name%', env('EMAIL_PROJECT_NAME'), $body);
        $mail_state = sendMail($_POST['email'], $_POST['username'], $body, 'Kundenkonto aktivieren');

        if($mail_state != true){
            $error = 'Die E-Mail konnte nicht versendet werden.';
            dd($mail_state);
        }
    }

    if(empty($error)) {

        $user_id = $user->create($helper->xssFix($_POST['username']), $helper->xssFix($_POST['email']), $_POST['password'],'pending','customer');

        $discord_webhook->callWebhook('Es hat sich ein neuer User mit dem Benutzernamen: ' . $_POST['username'] . ' registriert.');

        $user->renewSupportPin($user_id);

        $SQL = $db->prepare("UPDATE `customers` SET `verify_code` = :verify_code WHERE `id` = :user_id");
        $SQL->execute(array(":verify_code" => $verify_code, ":user_id" => $user_id));

        $_SESSION['success_msg'] = 'Kundenkonto wurde angelegt. Bitte bestätige deinen Kundenkonto per E-Mail.';
        header('Location: ' . env('URL') . 'auth/login/');
    } else {
        echo sendError($error);
    }

}