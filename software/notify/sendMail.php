<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function sendMail($email, $username, $mailContent, $mailSubject, $emailAltBody = null) {

    $mail = new PHPMailer(true);

    try {
        $mail->SMTPDebug = 0;
        $mail->isSMTP();
        $mail->SMTPAuth = true;
        $mail->SMTPSecure = 'tls';
        $mail->SMTPAutoTLS = true;

        // set login data
        $mail->Host = env('MAIL_HOST');
        $mail->Username = env('MAIL_USERNAME');
        $mail->Password = env('MAIL_PASSWORD');
        $mail->Port = env('MAIL_PORT');

        // add contact for send mail & set from
        $mail->setFrom(env('MAIL_USERNAME'), env('MAIL_NAME'));
        $mail->addAddress($email, $username);

        $mail->isHTML(true);
        #$mail->Priority = 1;
        #$mail->HeaderLine('X-Priority', $mail->Priority);
        $mail->Subject = $mailSubject;
        $mail->Body = $mailContent;
        $mail->AltBody = $emailAltBody;
        $mail->CharSet = 'utf-8';

        $mail->send();
        return true;
    } catch (\Exception $e) {
        return 'The message could not be sent. Mail-Error: ' . $mail->ErrorInfo;
    }
}