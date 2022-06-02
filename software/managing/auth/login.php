<?php

if(isset($_POST['login_submit'])) {
    $error = null;

    if(isset($_COOKIE['7apwy35m2budptd7']) && $_COOKIE['7apwy35m2budptd7'] == 'y') {
        $captcha_response = $site->validateCaptcha($_POST['h-captcha-response']);

        if($captcha_response == false) {
            $error = 'Ungültige Anfrage, bitte versuche es erneut. (ERR-Captcha)';
        }
    }

    if(empty($_POST['email'])) {
        $error = 'Bitte gib einen Benutzernamen / eine E-Mail ein.';
    }

    if(empty($_POST['password'])) {
        $error = 'Bitte gib ein Passwort ein.';
    }

    if(!$user->verifyLogin($_POST['email'], $_POST['password'])) {
        $error = 'Das angegebene Passwort stimmt nicht.';

        setcookie('7apwy35m2budptd7', 'y', time() + '1800', '/', env('COOKIE_DOMAIN'));
    }

    if($helper->getSetting('login') == 0) {
        if($user->getDataByLogin($_POST['email'], 'first' || 'second' || 'third' || 'admin')) {
            // nothing to do
        } else {
            $error = 'Der Login ist zurzeit deaktiviert.';
        }
    }

    if($user->verify($_POST['email']) == false) {
        $error = 'Zugangsdaten nicht gefunden.';
    }

    if($user->getState($_POST['email']) == 'pending') {
        $error = 'Bitte bestätige erst dein Kundenkonto.';
    }

    if($user->getState($_POST['email']) == 'banned') {
        $error = 'Dein Account ist gesperrt, bitte wende Dich an den Support.';
    }

    if(empty($error)) {
        // get user id
        $userid = $user->getDataByLogin($_POST['email'], 'id');

        if($user->getDataByLogin($_POST['email'], 'legal_accepted') == 1 || $_POST['legal_accepted'] == 1) {
            if($_POST['legal_accepted'] == 1) {
                $SQL = $db->prepare("UPDATE `customers` SET `legal_accepted` = :legal_accepted WHERE `email` = :email OR `username` = :username");
                $SQL->execute(array(":legal_accepted" => '1', ":email" => $_POST['email'], ":username" => $_POST['email']));
            }

            $SQL = $db->prepare("UPDATE `customers` SET `user_addr` = :user_addr WHERE `email` = :email OR `username` = :username");
            $SQL->execute(array(":user_addr" => $user->getIP(), ":email" => $_POST['email'], ":username" => $_POST['email']));

            $user->logLogin($userid, $user->getIP(), $user->getAgent(), $user->getLocation());

            if($user->getDataByLogin($_POST['email'], 'mail_login_notify') == '1') {
                $body = file_get_contents(BASE_PATH . 'public/email_templates/auth/login_notify.html');
                $body = str_replace('%username%', $user->getDataByLogin($_POST['email'], 'username'), $body);
                $body = str_replace('%ip_address%',  $user->getIP(), $body);
                $body = str_replace('%location%', $user->getLocation(), $body);
                $body = str_replace('%browser_agent%', $_SERVER['HTTP_USER_AGENT'], $body);
                $body = str_replace('%project_name%', env('EMAIL_PROJECT_NAME'), $body);
                $mail_state = sendMail($user->getDataByLogin($_POST['email'], 'email'), $user->getDataByLogin($_POST['email'], 'username'), $body, 'Login-Benachrichtigung');
            }

            $sessionId = $user->generateSessionToken($_POST['email']);

            if(isset($_POST['stayLogged'])){
                setcookie('session_token', $sessionId, time()+'864000','/', env('COOKIE_DOMAIN'));
            } else {
                setcookie('session_token', $sessionId, time()+'86400','/', env('COOKIE_DOMAIN'));
            }


            $_SESSION['success_msg'] = 'Dein Login war erfolgreich.';
            header('Location: ' . env('URL') . 'index/');
        } else {
            setcookie('7apwy35m2budptd7', null, time(), '/', env('COOKIE_DOMAIN'));

            echo "<script>
                   function accept() {
                       Swal.fire({
                           title: 'AGBs und Datenschutzbestimmungen',
                           text: 'Mit dem Klick auf Ja bestätigst du das du unsere AGBs & Datenschutzbestimmungen gelesen hast und diesen zustimmst.',
                           icon: 'warning',
                           showCancelButton: true,
                           confirmButtonColor: '#28a745',
                           cancelButtonColor: '#d33',
                           confirmButtonText: 'Ja, ich akzeptiere die AGBs und Datenschutzbestimmungen.',
                           cancelButtonText: 'Nein'
                       }).then((result) => {
                           if (result.value) {
                               document.getElementById('login_again').submit();
                           }
                       })
                   } accept();
                   </script>
                   <form method='post' id='login_again'>
                       <input hidden name='login_submit' value='1'>
                       <input hidden name='legal_accepted' value='1'>
                       <input hidden name='email' value='" . $_POST[email] . "'>
                       <input hidden name='password' value='" . $_POST[password] . "'>
                   </form>";
            }

    } else {
        echo sendError($error);
    }
}