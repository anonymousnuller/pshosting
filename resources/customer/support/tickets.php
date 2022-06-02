<?php
$currPage = 'customer_Deine Support-Tickets';
include BASE_PATH . 'software/controller/PageController.php';


if(isset($_POST['createTicket'])){
    $error = null;

    if(empty($_POST['title'])){
        $error = 'Bitte gebe einen Titel an';
    }

    if(empty($_POST['category'])){
        $error = 'Bitte wähle eine Kategorie';
    }

    if(empty($_POST['priority'])){
        $error = 'Bitte wähle eine Priorität';
    }

    if(empty($_POST['message'])){
        $error = 'Bitte gebe eine Nachricht an';
    }

    if($_POST['csrf_token'] != $_SESSION['csrf_token']){
        $error = 'Ungültige Anfrage bitte versuche es erneut!';
    }

    if($user->ticketCountMax($userid) >= $ticket_max){
        $error = 'Du hast das Ticket-Limit erreicht ('.$user->ticketCountMax($userid).'/'.$ticket_max.')';
    }


    if(empty($error)){

        $DB_SQL = $db;
        $SQL = $DB_SQL->prepare("INSERT INTO `support_tickets`(`user_id`, `categorie`, `priority`, `title`, `state`, `last_msg`, `product_category`, `product_id`) VALUES (:user_id, :categorie, :priority, :title, :status, :last_msg, :product_category, :product_id)");
        $SQL->execute(array(":user_id" => $userid, ":categorie" => $_POST['category'], ":priority" => $_POST['priority'], ":title" => $_POST['title'], ":status" => 'OPEN', ":last_msg" => 'CUSTOMER', ":product_category" => $_POST['product_category'], ":product_id" => $_POST['product_id']));
        $ticket_id = $DB_SQL->lastInsertId();

        $SQL = $db->prepare("INSERT INTO `support_tickets_messages`(`ticket_id`, `writer_id`, `message`) VALUES (:ticket_id,:writer_id,:message)");
        $SQL->execute(array(":ticket_id" => $ticket_id, ":writer_id" => $userid, ":message" => $_POST['message']));

        $discord_webhook->callWebhook(
            '
                Es wurde gerade ein neues Support-Ticket von ' . $username . " erstellt.
Informationen:
- Ticket-ID: " . $ticket_id. "
- Betreff: " . $_POST["title"] . "
- Prioritätsstufe: " . $_POST["priority"] . "
- Betreffendes Produkt: " . $_POST["product_id"] . " (" . $_POST["product_category"] . ")
- Nachricht:
" . $helper->nl2br2($_POST["message"]) . "

Link zum bearbeiten des Tickets: " . env('URL') . 'team/ticket/' . $ticket_id . '/' . ""
        );

        if($_POST['priority'] == 'LOW') {
            $discord_webhook->callWebhook('Beim Ticket mit der ID: ' . $ticket_id . ' wurde die Priorität: Niedrig ausgewählt. Bitte innerhalb 36 Stunden darauf antworten.');
        } elseif($_POST['priority'] == 'MIDDEL') {
            $discord_webhook->callWebhook('Beim Ticket mit der ID: ' . $ticket_id . ' wurde die Priorität: Mittel ausgewählt. Bitte innerhalb 12 Stunden darauf antworten.');
        } elseif($_POST['priority'] == 'HIGH') {
            $discord_webhook->callWebhook('Beim Ticket mit der ID: ' . $ticket_id . ' wurde die Priorität: Hoch ausgewählt. Bitte innerhalb 6 Stunden darauf antworten. @> Allgemeiner Support');
        } elseif($_POST['priority'] == 'SEHR') {
            $discord_webhook->callWebhook('Beim Ticket mit der ID: ' . $ticket_id . ' wurde die Priorität: Sehr hoch ausgewählt. Bitte innerhalb 3 Stunden darauf antworten - ermessens Handhabung! @here');
        } elseif($_POST['priority'] == 'ASAP') {
            $discord_webhook->callWebhook('Beim Ticket mit der ID: ' . $ticket_id . ' wurde die Priorität: Notfall (ASAP) ausgewählt. Bitte sofort reagieren. @everyone');
        }

        $SQL = $db -> prepare("SELECT * FROM `customers` WHERE `role` = 'first' || 'second' || 'third' || 'admin'");
        $SQL->execute();
        if ($SQL->rowCount() != 0) {
            while ($row = $SQL->fetch(PDO::FETCH_ASSOC)) {


                #include BASE_PATH.'app/notifications/mail_templates/support/new_ticket.php';
                #$mail_state = sendMail($row['email'], $row['username'], $mailContent, $mailSubject);

            }
        }


        if($_POST['category'] == 'allgemein') {
            $category = 'Allgemeine Abteilung';
        } elseif($_POST['category'] == 'technik') {
            $category = 'Technische Abteilung';
        } elseif($_POST['category'] == 'buchhaltung') {
            $category = 'Abteilung der Buchhaltung';
        } elseif($_POST['category'] == 'partner') {
            $category = 'Partnerschaftsabteilung';
        } else {
            $category = 'Sonstige Abteilung';
        }

        if($_POST['priority'] == 'LOW') {
            $priority = 'Niedrig (12-36 Std)';
        } elseif($_POST['priority'] == 'MIDDEL') {
            $priority = 'Mittel (6-12 Std)';
        } elseif($_POST['priority'] == 'HIGH') {
            $priority = 'Hoch (3-6 Std)';
        } elseif($_POST['priority'] == 'SEHR') {
            $priority = 'Sehr hoch (0-3 Std)';
        } elseif($_POST['priority'] == 'ASAP') {
            $priority = 'ASAP (Emergency, i.d.R. sofort)';
        }

         if($_POST['priority'] == 'LOW') {
            $team_answer = '12 bis 36 Stunden';
        } elseif($_POST['priority'] == 'MIDDEL') {
            $team_answer = '6 bis 12 Stunden';
        } elseif($_POST['priority'] == 'HIGH') {
            $team_answer = '3 bis 6 Stunden';
        } elseif($_POST['priority'] == 'SEHR') {
            $team_answer = '0 bis 3 Stunden';
        } elseif($_POST['priority'] == 'ASAP') {
            $team_answer = 'i.d.R. sofort';
        }

        $body = file_get_contents(BASE_PATH . 'public/email_templates/customer/support/ticket_created.html');
        $body = str_replace('%username%', $user->getDataById($userid, 'username'), $body);
        $body = str_replace('%id%',  $ticket_id, $body);
        $body = str_replace('%category%', $category, $body);
        $body = str_replace('%priority%', $priority, $body);
        $body = str_replace('%title%', $_POST['title'], $body);
        $body = str_replace('%message%', $helper->xssFix($_POST['message']), $body);
        $body = str_replace('%timeanswer%', $team_answer, $body);
        $body = str_replace('%project_name%', env('EMAIL_PROJECT_NAME'), $body);
        $mail_state = sendMail($user->getDataById($userid, 'email'), $user->getDataById($userid, 'username'), $body, '[Ticket: #'.$ticket_id.'] Support-Ticket erstellt');

        header('Location: '.env('URL').'support/ticket/'.$ticket_id . '/');
    } else {
        echo sendError($error);
    }
}

if(isset($_POST['reopen'])) {
    $error = null;

    if(is_null($_POST['ticket_id'])) {
        $error = 'Es wurde keine Ticket-ID angegeben.';
    }

    if(empty($error)) {

        $SQL = $db->prepare("UPDATE `support_tickets` SET `state` = :state WHERE `user_id` = :user_id AND `id` = :id");
        $SQL->execute(array(":state" => 'OPEN', ":user_id" => $userid, ":id" => $_POST['ticket_id']));

        echo sendSuccess('Das Ticket wurde erfolgreich geöffnet.');
    } else {
        echo sendError($error);
    }
}

//$s_pin = $user->renewSupportPin($userid);
?>
<form method="post">
    <input name="csrf_token" value="<?php $csrf_token = $site->generateCSRF(); echo $csrf_token; $_SESSION['csrf_token'] = $csrf_token; ?>" type="hidden">

    <div class="modal fade bd-example-modal-lg" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Neues Support-Ticket erstellen (Ticket-Limit: <?= $user->ticketCountMax($userid) ?> / <?= $ticket_max; ?>)</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <label>Betreff</label>
                    <input class="form-control form-control-solid" name="title" required="required">

                    <br>

                    <div class="row">
                        <div class="col-md-6">
                            <label>Kategorie</label>
                            <select class="form-select form-select-solid" name="category" required="required" data-control="select2" data-placeholder="Wähle eine Kategorie aus." data-allow-clear="true" tabindex="-1">
                                <option value="allgemein">Allgemein</option>
                                <option value="technik">Technik</option>
                                <option value="buchhaltung">Buchhaltung</option>
                                <option value="partner">Partner(-schaften)</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label>Priorität</label>
                            <select class="form-select form-select-solid" name="priority" required="required" data-control="select2" data-placeholder="Wähle eine Prioritätsstufe auf." data-allow-clear="true" tabindex="-1">
                                <option value="LOW">Niedrig (12-36 Std)</option>
                                <option value="MIDDEL" selected>Mittel (6-12 Std)</option>
                                <option value="HIGH">Hoch (3-6 Std)</option>
                                <option value="SEHR">Sehr hoch (0-3 Std)</option>
                                <?php if($user->getDataById($userid, 'asap_option') == '1') { ?>
                                    <option value="ASAP">Emergancy (i.d.R. sofort)</option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>


                    <div class="row">
                        <br>
                    </div>

                    <label for="product_id">Betreffend Produkt</label>
                    <input id="product_category" name="product_category" type="hidden" value=""/>
                    <select class="form-select form-select-solid" name="product_id" id="product_id" data-control="select2" data-placeholder="Wähle das betreffende Produkt aus." data-allow-clear="true" tabindex="-1">
                        <option></option>
                        <option selected>Kein Produkt</option>

                        

                        <?php
                        $SQL = $db->prepare("SELECT * FROM `lxc_servers` WHERE `user_id` = :user_id AND `deleted_at` IS NULL");
                        $SQL->execute(array(":user_id" => $userid));
                        if($SQL->rowCount() != 0) {
                            echo '<option disabled></option><optgroup label="LXC V-Server">';
                            while($lxc_row = $SQL->fetch(PDO::FETCH_ASSOC)) {
                                ?>
                                <option value="<?= $lxc_row['id']; ?>">
                                    <?php if(is_null($lxc_row['custom_name'])) { ?>
                                        #<?= $lxc_row['id']; ?> - <?= $lxc_row['hostname']; ?>
                                    <?php } else { ?>
                                        <?= $lxc_row['custom_name']; ?> - <?= $lxc_row['hostname']; ?>
                                    <?php } ?>
                                </option>
                            <?php } echo '</optgroup>'; } ?>

                        <?php
                        $SQL = $db->prepare("SELECT * FROM `kvm_servers` WHERE `user_id` = :user_id AND `deleted_at` IS NULL");
                        $SQL->execute(array(":user_id" => $userid));
                        if($SQL->rowCount() != 0) {
                            echo '<option disabled></option><optgroup label="KVM Rootserver" data-value="kvm">';
                            while($kvm_row = $SQL->fetch(PDO::FETCH_ASSOC)) {
                                ?>
                                <option value="<?= $kvm_row['id']; ?>">
                                    <?php if(is_null($kvm_row['custom_name'])) { ?>
                                        #<?= $kvm_row['id']; ?> - <?= $kvm_row['hostname']; ?>
                                    <?php } else { ?>
                                        <?= $kvm_row['custom_name']; ?> - <?= $kvm_row['hostname']; ?>
                                    <?php } ?>
                                </option>
                            <?php } echo '</optgroup>'; } ?>

                        <?php
                        $SQL = $db->prepare("SELECT * FROM `dedicated_servers` WHERE `user_id` = :user_id AND `deleted_at` IS NULL");
                        $SQL->execute(array(":user_id" => $userid));
                        if($SQL->rowCount() != 0) {
                            echo '<option disabled></option><optgroup label="Dedizierter Server">';
                            while($dedicated_row = $SQL->fetch(PDO::FETCH_ASSOC)) {
                                ?>
                                <option value="<?= $dedicated_row['id']; ?>">
                                    <?php if(is_null($dedicated_row['custom_name'])) { ?>
                                        #<?= $dedicated_row['id']; ?> - <?= $dedicated_row['hostname']; ?>
                                    <?php } else { ?>
                                        <?= $dedicated_row['custom_name']; ?> - <?= $dedicated_row['hostname']; ?>
                                    <?php } ?>
                                </option>
                            <?php } echo '</optgroup>'; } ?>

                        <?php
                        $SQL = $db->prepare("SELECT * FROM `webspaces` WHERE `user_id` = :user_id AND `deleted_at` IS NULL");
                        $SQL->execute(array(":user_id" => $userid));
                        if($SQL->rowCount() != 0) {
                            echo '<option disabled></option><optgroup label="Webspace">';
                            while($webspace_row = $SQL->fetch(PDO::FETCH_ASSOC)) {
                                ?>
                                <option value="<?= $webspace_row['id']; ?>">
                                    <?php if(is_null($webspace_row['custom_name'])) { ?>
                                        #<?= $webspace_row['id']; ?> - <?= $webspace_row['domainName']; ?>
                                    <?php } else { ?>
                                        <?= $webspace_row['custom_name']; ?> - <?= $webspace_row['domainName']; ?>
                                    <?php } ?>
                                </option>
                            <?php } echo '</optgroup>'; } ?>


                        <?php
                        $SQL = $db->prepare("SELECT * FROM `services` WHERE `user_id` = :user_id AND `deleted_at` IS NULL");
                        $SQL->execute(array(":user_id" => $userid));
                        if($SQL->rowCount() != 0) {
                            echo '<option></option><optgroup label="Services">';
                            while($services_row = $SQL->fetch(PDO::FETCH_ASSOC)) {
                                ?>
                                <option value="<?= $services_row['id']; ?>">
                                    <?php if(is_null($services_row['custom_name'])) { ?>
                                        #<?= $services_row['id']; ?> - <?= $services_row['name']; ?>
                                    <?php } else { ?>
                                        <?= $services_row['custom_name']; ?> - <?= $services_row['name']; ?>
                                    <?php } ?>
                                </option>
                            <?php } echo '</optgroup>'; } ?>
                    </select>

                    <script>
                        $(document).ready(function(){
                            $("#product_id").change(function(){
                                var s = $(this).find(":selected").closest("optgroup");
                                $("#product_category").val($(s).attr("data-value"));
                                //alert($(s).attr("data-value"));
                            })
                        });
                    </script>

                    <br>

                    <label>Beschreibung</label>
                    <textarea class="form-control from-control-solid" name="message" rows="5" required="required"></textarea>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-primary" data-bs-dismiss="modal"><i class="fas fa-times"></i> <b>Schließen</b></button>
                    <button type="submit" class="btn btn-success" name="createTicket"><i class="fas fa-share-square"></i> <b>Ticket erstellen</b></button>
                </div>
            </div>
        </div>
    </div>
</form>

<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
    <div class="post d-flex flex-column-fluid" id="kt_post">
        <div id="kt_content_container" class="container-xxl">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header align-items-center py-5 gap-2 gap-md-5">
                                <div class="card-title">
                                    <!--begin::Search-->
                                    <div class="d-flex align-items-center position-relative my-1">
                                        <h3 class="card-title">
                                            Deine Support-Tickets
                                        </h3>
                                    </div>
                                    <!--end::Search-->
                                    <!--begin::Export buttons-->
                                    <div id="kt_datatable_example_1_export" class="d-none"></div>
                                    <!--end::Export buttons-->
                                </div>
                                <div class="card-toolbar flex-row-fluid justify-content-end gap-5">
                                    <!--begin::Export dropdown-->
                                    <button type="button" class="btn btn-light-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
                                        <i class="fas fa-share-square"></i> Neues Ticket erstellen
                                    </button>

                                </div>
                            </div>
                            <div class="card-body d-flex flex-column">
                                <table class="table align-middle border rounded table-row-dashed fs-6 g-5" id="kt_datatable_example_2">
                                    <thead>
                                    <tr class="text-start text-gray-900 fw-bolder fs-7 text-uppercase">
                                        <th scope="col">
                                            #
                                        </th>
                                        <th scope="col">
                                            Betreff
                                        </th>
                                        <th scope="col">
                                            Priorität
                                        </th>
                                        <th scope="col">
                                            Status
                                        </th>
                                        <th scope="col">
                                            Letzte Antwort
                                        </th>
                                        <th scope="col">
                                            Letztes Update
                                        </th>
                                        <th scope="col" class="text-end">

                                        </th>
                                    </tr>
                                    </thead>
                                    <tbody class="fw-bold text-gray-700">
                                    <?php
                                    $SQL = $db -> prepare("SELECT * FROM `support_tickets` WHERE `user_id` = :user_id AND (`state` in ('OPEN', 'PROCESSING', 'WAITINGC', 'WAITINGI')) ORDER BY `id` DESC");
                                    $SQL->execute(array(":user_id" => $userid));
                                    if ($SQL->rowCount() != 0) {
                                        while ($row = $SQL -> fetch(PDO::FETCH_ASSOC)){

                                            if($row['state'] == 'OPEN'){
                                                $status = '<span class="badge badge-success">Offen</span>';
                                            } elseif($row['state'] == 'PROCESSING') {
                                                $status = '<span class="badge badge-warning">In Bearbeitung</span>';
                                            } elseif($row['state'] == 'CLOSED'){
                                                $status = '<span class="badge badge-danger">Geschlossen</span>';
                                            } elseif($row['state'] == 'WAITINGC') {
                                                $status = '<span class="badge badge-info">Warte auf Kunden-Reaktion</span>';
                                            } elseif($row['state'] == 'WAITINGI') {
                                                $status = '<span class="badge badge-info">Warte auf Inhaber-Reaktion</span>';
                                            }

                                            if($row['last_msg'] == 'CUSTOMER'){
                                                $last_msg = '<span class="badge badge-secondary">Kunden-Antwort</span>';
                                            } elseif($row['last_msg'] == 'SUPPORT'){
                                                $last_msg = '<span class="badge badge-info">Support-Antwort</span>';
                                            }

                                            if($row['priority'] == 'LOW'){
                                                $priority = 'Niedrig';
                                            } elseif($row['priority'] == 'MIDDEL'){
                                                $priority = 'Mittel';
                                            } elseif($row['priority'] == 'HIGH'){
                                                $priority = 'Hoch';
                                            } elseif($row['priority'] == 'SEHR') {
                                                $priority = 'Sehr hoch';
                                            } elseif($row['priority'] == 'ASAP') {
                                                $priority = 'Emergency';
                                            }


                                            $date = new DateTime($row['updated_at'], new DateTimeZone('Europe/Berlin'));
                                            $date = $date->format('Y-m-d H:i:s');
                                            $hours_new = date('G', strtotime($date)); // In 24-hour format of an hour without leading zeros
                                            // For current timestamp

                                            $last_id = $site->getLastTicketReactionID($row['id'], $userid);
                                            $last_updated = $site->getLastTicketReaction($row['ticket_id'], $userid);

                                            $updated_at = $helper->formatDateEng($row['updated_at']);

                                            ?>
                                            <tr>
                                                <th scope="row"><?= $row['id']; ?></th>
                                                <td><?= $helper->xssFix($row['title']); ?></td>
                                                <td><?= $priority; ?></td>
                                                <td><?= $status; ?></td>
                                                <td><?= $last_msg; ?></td>
                                                <td><span id="countdown<?= $row['id']; ?>">Lädt...</span></td>
                                                <td><a href="<?= $helper->url(); ?>support/ticket/<?= $row['id']; ?>/" class="btn btn-outline-primary btn-sm font-weight-bolder"><i class="fas fa-eye"></i> Anschauen</a></td>
                                            </tr>

                                            <script>
                                                // now to created at
                                                var countDownDate<?= $row['id']; ?> = new Date("<?= $row['updated_at']; ?>").getTime();
                                                var x<?= $row['id']; ?> = setInterval(function() {

                                                    var now<?= $row['id']; ?> = new Date().getTime();
                                                    var distance<?= $row['id']; ?> = now<?= $row['id']; ?> - countDownDate<?= $row['id']; ?>;

                                                    var days = Math.floor(distance<?= $row['id']; ?> / (1000 * 60 * 60 * 24));
                                                    var hours = Math.floor((distance<?= $row['id']; ?> % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                                                    var minutes = Math.floor((distance<?= $row['id']; ?> % (1000 * 60 * 60)) / (1000 * 60));
                                                    var seconds = Math.floor((distance<?= $row['id']; ?> % (1000 * 60)) / 1000);

                                                    var hours_end = Math.floor((days * 24) + hours);

                                                    if(days == 1){ var days_text = ' Tag' } else { var days_text = ' Tage'; }
                                                    if(hours_end == 1){ var hours_text = ' Stunde' } else { var hours_text = ' Stunden'; }
                                                    if(minutes == 1){ var minutes_text = ' Minute' } else { var minutes_text = ' Minuten'; }
                                                    if(seconds == 1){ var seconds_text = ' Sekunde' } else { var seconds_text = ' Sekunden'; }

                                                    if(!(days == 0 && hours_end == 0 && minutes == 0 && seconds == 0)) {
                                                        $('#countdown<?= $row["id"]; ?>').html('vor ' + days + days_text);
                                                        if (days == 0 && !(hours_end == 0 && minutes == 0 && seconds == 0)) {
                                                            $('#countdown<?= $row["id"]; ?>').html('vor ' + hours_end + hours_text);
                                                            if (days == 0 && hours_end == 0 && !(minutes == 0 && seconds == 0)) {
                                                                $('#countdown<?= $row["id"]; ?>').html('vor ' + minutes + minutes_text);
                                                                if (days == 0 && hours_end == 0 && minutes == 0 && !(seconds == 0)) {
                                                                    $('#countdown<?= $row["id"]; ?>').html('gerade eben');
                                                                }
                                                            }
                                                        }
                                                    } else {
                                                        $('#countdown<?= $row["id"]; ?>').html('vor ' +hours_end+hours_text);
                                                    }

                                                    if (distance<?= $row['id']; ?> <= 0) {
                                                        clearInterval(x<?= $row['id']; ?>);
                                                    }
                                                }, 1000);
                                            </script>
                                        <?php } } ?>
                                    </tbody>
                                </table>

                                <script type="text/javascript">
                                    $("#kt_datatable_example_2").DataTable({
                                        "scrollY": "500px",
                                        "scrollCollapse": true,
                                        "paging": false,
                                        "dom": "<'table-responsive'tr>",
                                        "order": [[ 0, "desc" ]]
                                    });
                                </script>
                            </div>
                        </div>
                    </div>
                </div>


                <?php
                $SQL = $db->prepare("SELECT * FROM `support_tickets` WHERE `user_id` = :user_id AND `state` = :state");
                $SQL->execute(array(":user_id" => $userid, ":state" => 'CLOSED'));
                if($SQL->rowCount() != 0) {
                    while($row = $SQL->fetch(PDO::FETCH_ASSOC)) {
                        ?>

                        <div class="row">
                            <br>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-header align-items-center py-5 gap-2 gap-md-5">
                                        <div class="card-title">
                                            <div class="d-flex align-items-center position-relative my-1">
                                                <h3 class="card-title">
                                                    Deine geschlossenen Support-Tickets
                                                </h3>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body d-flex flex-column">
                                        <table class="table align-middle border rounded table-row-dashed fs-6 g-5" id="kt_datatable_2">
                                            <thead>
                                            <tr class="text-start text-gray-900 fw-bolder fs-7 text-uppercase">
                                                <th scope="col">
                                                    #
                                                </th>
                                                <th scope="col">
                                                    Betreff
                                                </th>
                                                <th scope="col">
                                                    Status
                                                </th>
                                                <th scope="col">
                                                    Letzte Antwort
                                                </th>
                                                <th scope="col">
                                                    Letztes Update
                                                </th>
                                                <th scope="col" class="text-end">

                                                </th>
                                            </tr>
                                            </thead>
                                            <tbody class="fw-bold text-gray-700">
                                            <?php
                                            $SQL = $db -> prepare("SELECT * FROM `support_tickets` WHERE `user_id` = :user_id AND `state` = 'CLOSED' ORDER BY `id` DESC");
                                            $SQL->execute(array(":user_id" => $userid));
                                            if ($SQL->rowCount() != 0) {
                                                while ($row = $SQL -> fetch(PDO::FETCH_ASSOC)){

                                                    if($row['state'] == 'OPEN'){
                                                        $status = '<span class="badge badge-success">Offen</span>';
                                                    } elseif($row['state'] == 'PROCESSING') {
                                                        $status = '<span class="badge badge-warning">In Bearbeitung</span>';
                                                    } elseif($row['state'] == 'CLOSED'){
                                                        $status = '<span class="badge badge-danger">Geschlossen</span>';
                                                    }

                                                    if($row['last_msg'] == 'CUSTOMER'){
                                                        $last_msg = '<span class="badge badge-secondary">Kunden-Antwort</span>';
                                                    } elseif($row['last_msg'] == 'SUPPORT'){
                                                        $last_msg = '<span class="badge badge-info">Support-Antwort</span>';
                                                    }

                                                    if($row['priority'] == 'LOW'){
                                                        $priority = 'Niedrig';
                                                    } elseif($row['priority'] == 'MITTEL'){
                                                        $priority = 'Mittel';
                                                    } elseif($row['priority'] == 'HOCH'){
                                                        $priority = 'Hoch';
                                                    } elseif($row['priority'] == 'SEHR') {
                                                        $priority = 'Sehr hoch';
                                                    } elseif($row['priority'] == 'ASAP') {
                                                        $priority = 'Emergency';
                                                    }


                                                    $date = new DateTime($row['updated_at'], new DateTimeZone('Europe/Berlin'));
                                                    $date = $date->format('Y-m-d H:i:s');
                                                    $hours_new = date('G', strtotime($date)); // In 24-hour format of an hour without leading zeros
                                                    // For current timestamp

                                                    $last_id = $site->getLastTicketReactionID($row['id'], $userid);
                                                    $last_updated = $site->getLastTicketReaction($row['ticket_id'], $userid);

                                                    $updated_at = $helper->formatDateEng($row['updated_at']);

                                                    ?>
                                                    <tr>
                                                        <th scope="row"><?= $row['id']; ?></th>
                                                        <td><?= $helper->xssFix($row['title']); ?></td>
                                                        <td><?= $status; ?></td>
                                                        <td><?= $last_msg; ?></td>
                                                        <td><span id="countdown<?= $row['id']; ?>">Lädt...</span></td>
                                                        <td>
                                                            <form method="post" id="reopenTicket<?= $row['id']; ?>">
                                                                <input hidden="hidden" name="reopen">
                                                                <input hidden="hidden" name="ticket_id" value="<?= $row['id']; ?>">

                                                                <button type="button" class="btn btn-outline-primary btn-sm" onclick="reopen<?= $row['id']; ?>();">
                                                                    <i class="fas fa-lock-open"></i> Ticket wieder eröffnen
                                                                </button>
                                                            </form>
                                                        </td>
                                                    </tr>

                                                    <script>
                                                        // now to created at
                                                        var countDownDate<?= $row['id']; ?> = new Date("<?= $row['updated_at']; ?>").getTime();
                                                        var x<?= $row['id']; ?> = setInterval(function() {

                                                            var now<?= $row['id']; ?> = new Date().getTime();
                                                            var distance<?= $row['id']; ?> = now<?= $row['id']; ?> - countDownDate<?= $row['id']; ?>;

                                                            var days = Math.floor(distance<?= $row['id']; ?> / (1000 * 60 * 60 * 24));
                                                            var hours = Math.floor((distance<?= $row['id']; ?> % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                                                            var minutes = Math.floor((distance<?= $row['id']; ?> % (1000 * 60 * 60)) / (1000 * 60));
                                                            var seconds = Math.floor((distance<?= $row['id']; ?> % (1000 * 60)) / 1000);

                                                            var hours_end = Math.floor((days * 24) + hours);

                                                            if(days == 1){ var days_text = ' Tag' } else { var days_text = ' Tage'; }
                                                            if(hours_end == 1){ var hours_text = ' Stunde' } else { var hours_text = ' Stunden'; }
                                                            if(minutes == 1){ var minutes_text = ' Minute' } else { var minutes_text = ' Minuten'; }
                                                            if(seconds == 1){ var seconds_text = ' Sekunde' } else { var seconds_text = ' Sekunden'; }

                                                            if(!(days == 0 && hours_end == 0 && minutes == 0 && seconds == 0)) {
                                                                $('#countdown<?= $row["id"]; ?>').html('vor ' + days + days_text);
                                                                if (days == 0 && !(hours_end == 0 && minutes == 0 && seconds == 0)) {
                                                                    $('#countdown<?= $row["id"]; ?>').html('vor ' + hours_end + hours_text);
                                                                    if (days == 0 && hours_end == 0 && !(minutes == 0 && seconds == 0)) {
                                                                        $('#countdown<?= $row["id"]; ?>').html('vor ' + minutes + minutes_text);
                                                                        if (days == 0 && hours_end == 0 && minutes == 0 && !(seconds == 0)) {
                                                                            $('#countdown<?= $row["id"]; ?>').html('gerade eben');
                                                                        }
                                                                    }
                                                                }
                                                            } else {
                                                                $('#countdown<?= $row["id"]; ?>').html('vor ' +hours_end+hours_text);
                                                            }

                                                            if (distance<?= $row['id']; ?> <= 0) {
                                                                clearInterval(x<?= $row['id']; ?>);
                                                            }
                                                        }, 1000);
                                                    </script>

                                                    <script type="text/javascript">
                                                        function reopen<?= $row['id']; ?>() {
                                                            Swal.fire({
                                                                text: "Bist Du dir Sicher, dass Du das Ticket wieder eröffnen möchtest?",
                                                                icon: "question",
                                                                buttonStyling: false,
                                                                showCancelButton: true,
                                                                confirmButtonText: "Ja, bin ich!",
                                                                cancelButtonText: "Nein, abbrechen.",
                                                                customClass: {
                                                                    confirmButton: "btn btn-success",
                                                                    cancelButton: "btn btn-outline-primary"
                                                                }
                                                            }).then((result) => {
                                                                if(result.isConfirmed) {
                                                                    document.getElementById('reopenTicket<?= $row['id']; ?>').submit();
                                                                }
                                                            });
                                                        }
                                                    </script>
                                                <?php } } ?>
                                            </tbody>
                                        </table>


                                        <script type="text/javascript">
                                            $("#kt_datatable_2").DataTable({
                                                "scrollY": "500px",
                                                "scrollCollapse": true,
                                                "paging": false,
                                                "dom": "<'table-responsive'tr>",
                                                "order": [[ 0, "desc" ]]
                                            });
                                        </script>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php } } ?>
            </div>
        </div>
    </div>
</div>