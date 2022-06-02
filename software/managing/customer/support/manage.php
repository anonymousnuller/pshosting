<?php

$ticket_id = $helper->protect($_GET['id']);
$SQL = $db->prepare("SELECT * FROM `support_tickets` WHERE `id` = :ticket_id");
$SQL->execute(array(":ticket_id" => $ticket_id));
$ticketInfos = $SQL -> fetch(PDO::FETCH_ASSOC);

if($userid != $ticketInfos['user_id']){
    die(header('Location: '.$helper->url().'support/tickets/'));
}

$writer_id = $userid;

if(isset($_POST['answerTicket'])){
    $error = null;

    if($_POST['csrf_token'] != $_SESSION['csrf_token']){
        $error = 'Ungültige Anfrage bitte versuche es erneut!';
    }

    if(empty($_POST['message'])) {
        $error = 'Bitte gebe eine Nachricht an';
    }

    if(empty($error)){
        $SQL = $db->prepare("INSERT INTO `support_tickets_messages`(`ticket_id`, `writer_id`, `message`) VALUES (:ticket_id,:writer_id,:message)");
        $SQL->execute(array(":ticket_id" => $ticket_id, ":writer_id" => $writer_id, ":message" => $_POST['message']));


        $SQL = $db->prepare("UPDATE `support_tickets` SET `last_msg` = 'CUSTOMER' WHERE `id` = :id");
        $SQL->execute(array(":id" => $ticket_id));


        #include BASE_PATH.'app/notifications/mail_templates/support/new_user_response.php';
        #$mail_state = sendMail($mail, $username, $mailContent, $mailSubject);

        echo sendSuccess('Deine Antwort wurde an das Team übermittelt');
    } else {
        echo sendError($error);
    }
}

$state = $ticketInfos['state'];

if($state == 'OPEN'){
    $plain_state = 'Offen';
} elseif($state == 'PROCESSING'){
    $plain_state = 'In Bearbeitung';
} elseif($state == 'CLOSED'){
    $plain_state = 'Geschlossen';
}

if($ticketInfos['last_msg'] == 'CUSTOMER'){
    $last_msg = '<span class="badge badge-secondary">Kunden-Antwort</span>';
} elseif($ticketInfos['last_msg'] == 'SUPPORT'){
    $last_msg = '<span class="badge badge-info">Support-Antwort</span>';
}

if (isset($_POST['closeTicket'])) {
    $SQL = $db->prepare("UPDATE `support_tickets` SET `state` = 'CLOSED' WHERE `id` = :id");
    $SQL->execute(array(":id" => $ticket_id));

    $SQL = $db->prepare("INSERT INTO `support_tickets_messages`(`ticket_id`, `writer_id`, `message`, `type`) VALUES (:ticket_id,:writer_id,:message,:type)");
    $SQL->execute(array(":ticket_id" => $ticket_id, ":writer_id" => $userid, ":message" => 'Das Support-Ticket wurde vom Kunden geschlossen.', ":type" => 'log'));
	
	$body = file_get_contents(BASE_PATH . 'public/email_templates/customer/support/ticket_closed.html');
    $body = str_replace('%username%', $user->getDataById($userid, 'username'), $body);
    $body = str_replace('%id%',  $ticket_id, $body);
    $body = str_replace('%message%', 'Du selbst hast', $body);
    $body = str_replace('%project_name%', env('EMAIL_PROJECT_NAME'), $body);
    $mail_state = sendMail($user->getDataById($userid, 'email'), $user->getDataById($userid, 'username'), $body, '[Ticket: #'.$ticket_id.'] Support-Ticket wurde geschlossen');


    echo sendSuccess('Ticket wurde geschlossen.');
    header("Refresh:2;url=" . $helper->url() . 'support/ticket/' . $ticket_id . '/');
}

if(isset($_POST['openTicket'])){
    $SQL = $db->prepare("UPDATE `support_tickets` SET `state` = 'OPEN' WHERE `id` = :id");
    $SQL->execute(array(":id" => $ticket_id));

    $SQL = $db->prepare("INSERT INTO `support_tickets_messages`(`ticket_id`, `writer_id`, `message`, `type`) VALUES (:ticket_id,:writer_id,:message,:type)");
    $SQL->execute(array(":ticket_id" => $ticket_id, ":writer_id" => $userid, ":message" => 'Das Support-Ticket wurde vom Kunden geöffnet.', ":type" => 'log'));

    echo sendSuccess('Ticket wurde geöffnet.');
    header("Refresh:2;url=" . $helper->url() . 'support/ticket/' . $ticket_id . '/');
}
