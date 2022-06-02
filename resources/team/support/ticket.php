<?php
$currPage = 'admin_Ticket Support';
include BASE_PATH.'software/controller/PageController.php';

$date = new DateTime(null, new DateTimeZone('Europe/Berlin'));
$dateTimeNow = $date->format('Y-m-d H:i:s');

$ticket_id = $helper->protect($_GET['id']);
$SQL = $db->prepare("SELECT * FROM `support_tickets` WHERE `id` = :ticket_id");
$SQL->execute(array(":ticket_id" => $ticket_id));
$ticketInfos = $SQL -> fetch(PDO::FETCH_ASSOC);

$SQL = $db->prepare("SELECT * FROM `customers` WHERE `support_pin` = :s_pin");
$SQL->execute(array(":s_pin" => $spin));
$userInfos = $SQL -> fetch(PDO::FETCH_ASSOC);

if($user->getDataByUsername($username, 'role') == 'admin') {
    $team = 'Inhaber';
} elseif($user->getDataByUsername($username, 'role') == 'third') {
    $team = '3rd-Level Support';
} elseif($user->getDataByUsername($username, 'role') == 'second') {
    $team = '2nd-Level Support';
} elseif($user->getDataByUsername($username, 'role') == 'first') {
    $team = '1st-Level Support';
}

$id = $userInfos['id'];

$writer_id = $userid;
$user_id_ticket = $ticketInfos['user_id'];

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

        $SQL2 = $db->prepare("UPDATE `support_tickets` SET `last_msg` = 'SUPPORT' WHERE `id` = :id");
        $SQL2->execute(array(":id" => $ticket_id));

        $SQL2 = $db->prepare("UPDATE `support_tickets` SET `updated_at` = :updated_at WHERE `id` = :id");
        $SQL2->execute(array(":updated_at" => $dateTimeNow, ":id" => $ticket_id));

        if($ticketInfos['state'] == 'OPEN') {
            $SQL = $db->prepare("UPDATE `support_tickets` SET `state` = 'PROCESSING' WHERE `id` = :id");
            $SQL->execute(array(":id" => $ticket_id));
        }

        
        $body = file_get_contents(BASE_PATH . 'public/email_templates/customer/support/ticket_answer.html');
        $body = str_replace('%username%', $user->getDataById($user_id_ticket, 'username'), $body);
        $body = str_replace('%id%',  $ticket_id, $body);
        $body = str_replace('%title%', $_POST['title'], $body);
        $body = str_replace('%message%', $helper->nl2br2($_POST['message']), $body);
        $body = str_replace('%team%', $user->getDataById($writer_id, 'firstname') . ' ' . $user->getDataById($writer_id, 'lastname'), $body);
        $body = str_replace('%project_name%', env('EMAIL_PROJECT_NAME'), $body);
        $mail_state = sendMail($user->getDataById($user_id_ticket, 'email'), $user->getDataById($user_id_ticket, 'username'), $body, '[Ticket: #'.$ticket_id.'] Antwort auf dein Support-Ticket');


        echo sendSuccess('Deine Antwort wurde an den Kunden übermittelt');
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
    $last_msg = 'Kunde-Antwort';
} elseif($ticketInfos['last_msg'] == 'SUPPORT'){
    $last_msg = 'Support-Antwort';
}

if(isset($_POST['closeTicket'])){
    $SQL = $db->prepare("INSERT INTO `support_tickets_messages`(`ticket_id`, `writer_id`, `message`, `type`) VALUES (:ticket_id,:writer_id,:message,:type)");
    $SQL->execute(array(":ticket_id" => $ticket_id, ":writer_id" => $userid, ":message" => 'Das Support-Ticket wurde von einem Mitarbeiter geschlossen.', ":type" => 'log'));

    $SQL = $db->prepare("UPDATE `support_tickets` SET `state` = 'CLOSED' WHERE `id` = :id");
    $SQL->execute(array(":id" => $ticket_id));

    $state = 'CLOSED';
	
	
    $body = file_get_contents(BASE_PATH . 'public/email_templates/customer/support/ticket_closed.html');
    $body = str_replace('%username%', $user->getDataById($user_id_ticket, 'username'), $body);
    $body = str_replace('%id%',  $ticket_id, $body);
    $body = str_replace('%message%', 'Ein Mitarbeiter hat', $body);
    $body = str_replace('%project_name%', env('EMAIL_PROJECT_NAME'), $body);
    $mail_state = sendMail($user->getDataById($user_id_ticket, 'email'), $user->getDataById($user_id_ticket, 'username'), $body, '[Ticket: #'.$ticket_id.'] Support-Ticket wurde geschlossen');



    echo sendSuccess('Das Ticket wurde geschlossen');
}

if(isset($_POST['openTicket'])){
    $SQL = $db->prepare("INSERT INTO `support_tickets_messages`(`ticket_id`, `writer_id`, `message`, `type`) VALUES (:ticket_id,:writer_id,:message,:type)");
    $SQL->execute(array(":ticket_id" => $ticket_id, ":writer_id" => $userid, ":message" => 'Das Support-Ticket wurde vom Mitarbeiter geöffnet.', ":type" => 'log'));

    $SQL = $db->prepare("UPDATE `support_tickets` SET `state` = 'OPEN' WHERE `id` = :id");
    $SQL->execute(array(":id" => $ticket_id));

    $state = 'OPEN';

    echo sendSuccess('Das Ticket wurde geöffnet');
}


?>
<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
    <div class="post d-flex flex-column-fluid" id="kt_post">
        <div id="kt_content_container" class="container-xxl">
            <div class="container">

                <div class="row">

                    <div class="col-md-12">
                        <div class="card card-body">
                            <div class="row">
                                <div class="col-md-2">
                                    Ticket-ID: #<?= $ticket_id; ?>
                                </div>
                                <div class="col-md-3">
                                    Status: <?= $plain_state; ?>
                                </div>
                                <div class="col-md-3">
                                    Letzte Antwort: <?= $last_msg; ?>
                                </div>
                                <div class="col-md-4">
                                    Erstellt am: <?= $helper->formatDate($ticketInfos['created_at']); ?>
                                </div>

                                <br><br>
                                <?php
                                $SQL = $db -> prepare("SELECT * FROM `support_tickets` WHERE `id` = :id");
                                $SQL->execute(array(":id" => $ticket_id));
                                if ($SQL->rowCount() != 0) {
                                while ($row = $SQL -> fetch(PDO::FETCH_ASSOC)){?>

                                <div class="col-md-2">
                                    UN/ID: <?= $user->getDataById($row['user_id'], 'username'); ?> / <?= $user->getDataById($row['user_id'], 'id'); ?>
                                </div>

                                <div class="col-3">
                                    Support-PIN: <?= $user->getDataById($row['user_id'], 's_pin'); ?> <i style="cursor: pointer;" class="fas fa-copy copy-btn" data-clipboard-text="<?= $user->getDataById($row['user_id'], 's_pin'); ?>" data-toggle="tooltip" title="Support-PIN kopieren"></i>
                                </div>

                                <div class="col-3">
                                    Support-PIN Login: <a href="<?= env('URL'); ?>team/spin_login/">hier klicken</a>
                                </div>

                                <div class="col-4">

                                </div>
                                <?php } } ?>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12"> <br> </div>

                    <?php
                    $SQL = $db -> prepare("SELECT * FROM `support_tickets_messages` WHERE `ticket_id` = :ticket_id");
                    $SQL->execute(array(":ticket_id" => $ticket_id));
                    if ($SQL->rowCount() != 0) {
                        while ($row = $SQL -> fetch(PDO::FETCH_ASSOC)){
                            $writer_token = $user->getDataById($row['writer_id'],'session_token');
                            if($user->isInTeam($writer_token) == true){ ?>
                                <div class="col-md-6"></div>
                                <div class="col-md-6">
                                    <div class="card">
                                        <div class="card-body">
                                            <p><?= $helper->nl2br2($row['message']); ?></p>
                                            <small style="float: right;"><?= $user->getDataById($row['writer_id'], 'username'); ?> schrieb am <?= $helper->formatDate($row['created_at']); ?></small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12"> <br> </div>
                            <?php } else { ?>
                                <div class="col-md-6">
                                    <div class="card">
                                        <div class="card-body">
                                            <p><?= $helper->nl2br2($row['message']); ?></p>
                                            <small style="float: right;"><?= $user->getDataById($row['writer_id'], 'username'); ?> schrieb am <?= $helper->formatDate($row['created_at']); ?></small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6"></div>
                                <div class="col-md-12"> <br> </div>
                            <?php } } } ?>

                    <?php if($state == 'OPEN'){ ?>
                        <div class="col-md-12">
                            <form method="post">
                                <input name="csrf_token" value="<?php $csrf_token = $site->generateCSRF(); echo $csrf_token; $_SESSION['csrf_token'] = $csrf_token; ?>" type="hidden">

                                <textarea style="resize: none;" rows="14" name="message" class="form-control">Hallo,&#10;&#10;&#10;&#10;Liebe Grüße,&#10;<?= $user->getDataByUsername($username, 'firstname'); ?> <?= $user->getDataByUsername($username, 'lastname'); ?> (<?= $team; ?>)</textarea>
                                <br>
                                <button type="submit" name="answerTicket" class="btn btn-outline-success btn-lg font-weight-bolder"><i class="fas fa-reply"></i> Antworten</button>&nbsp;
                                <button name="closeTicket" type="submit" class="btn btn-outline-primary btn-lg font-weight-bolder"><i class="fas fa-times"></i> Ticket schließen</button>
                            </form>

                        </div>
                    <?php } elseif($state == 'PROCESSING' || 'WAITINGC' || 'WAITINGI') { ?>

                        <div class="col-md-12">
                            <form method="post">
                                <input name="csrf_token" value="<?php $csrf_token = $site->generateCSRF(); echo $csrf_token; $_SESSION['csrf_token'] = $csrf_token; ?>" type="hidden">

                                <textarea style="resize: none;" rows="14" name="message" class="form-control">Hallo,&#10;&#10;&#10;&#10;Liebe Grüße,&#10;<?= $user->getDataByUsername($username, 'firstname'); ?> <?= $user->getDataByUsername($username, 'lastname'); ?> (<?= $team; ?>)</textarea>
                                <br>
                                <button type="submit" name="answerTicket" class="btn btn-outline-success btn-lg font-weight-bolder"><i class="fas fa-reply"></i> Antworten</button>&nbsp;
                                <button name="closeTicket" type="submit" class="btn btn-outline-primary btn-lg font-weight-bolder"><i class="fas fa-times"></i> Ticket schließen</button>
                            </form>

                        </div>
                    <?php } else { ?>

                        <div class="col-md-12">
                            <!--div class="alert alert-primary text-center" role="alert">
                                Das Ticket ist geschlossen!
                            </div-->

                            <div class="col-md-12">
                                <?php if($darkmode){ ?>
                                    <div class="alert alert-light text-center" role="alert">
                                <?php } else { ?>
                                    <div class="alert alert-light text-center" role="alert">
                                <?php } ?>
                                    <h1 class="alert-heading">
                                        <br>
                                        Das Ticket ist geschlossen! 🔒
                                    </h1>
                                    <br>
                                    <h4>
                                        Dieses Ticket wurde erfolgreich bearbeitet und das Problem des Kundens wurde gelöst,<br>
                                        Du hast die Möglichkeit das Ticket wieder zu öffnen sofern noch handlungsbedarf besteht.
                                    </h4>
                                    <br>
                                    <p>
                                        <form method="post">
                                            <button name="openTicket" type="submit" class="btn btn-outline-success btn-lg font-weight-bolder"><i class="fas fa-lock-open"></i>&nbsp; Ticket wieder öffnen</button>&nbsp;
                                            <a href="<?= env('URL'); ?>team/tickets/" class="btn btn-outline-primary btn-lg font-weight-bolder"><i class="fas fa-clipboard-list"></i>&nbsp; Zur Ticket-Übersicht</a>
                                        </form>
                                    </p>
                                </div>
                            </div>
                        </div>

                    <?php } ?>

                </div>

            </div>
        </div>
    </div>
</div>