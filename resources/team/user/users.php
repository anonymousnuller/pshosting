<?php
$currPage = 'team_Benutzerverwaltung_admin';
include BASE_PATH.'software/controller/PageController.php';

if(isset($_POST['createUser'])){

    if($user->exists($_POST['email'])){
        $error = 'Die E-Mail ist bereits vergeben';
    }

    if(empty($error)){

        $password = $helper->generateRandomString('26');
        $user_id = $user->create($_POST['username'], $_POST['email'], $password,'active');

        $user->addMoney($_POST['amount'], $user_id);
        if($_POST['amount'] > 0){
            $user->addOrder($user_id, $_POST['amount'],'Beta Startguthaben');
        }

        /*include BASE_PATH.'app/notifications/mail_templates/auth/beta_user.php';
        $mail_state = sendMail($_POST['email'], $_POST['username'], $mailContent, $mailSubject, $emailAltBody);
*/
        echo sendSuccess('Benutzer wurde angelegt');

    } else {
        echo sendError($error);
    }
}

?>
<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
    <div class="post d-flex flex-column-fluid" id="kt_post">
        <div id="kt_content_container" class="container-xxl">
            <div class="container">
                <div class="row">

                    <div class="col-md-12">

                        <div class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#allmails"><i class="far fa-list-alt"></i> Alle E-Mails</div>
                        <div class="card">
                            <div class="card-body d-flex flex-column">
                                <table class="table" id="dataTableLoad">
                                    <thead>
                                    <tr>
                                        <th scope="col">
                                            #
                                        </th>
                                        <th scope="col">
                                            Benutzername
                                        </th>
                                        <th scope="col">
                                            E-Mail
                                        </th>
                                        <th scope="col">
                                            Guthaben
                                        </th>
                                        <th scope="col">
                                            Kunde seit
                                        </th>
                                        <th scope="col">

                                        </th>
                                    </tr>
                                    </thead>
                                    <tbody class="list">
                                    <?php
                                    $SQL = $db -> prepare("SELECT * FROM `customers`");
                                    $SQL->execute();
                                    if ($SQL->rowCount() != 0) {
                                        while ($row = $SQL -> fetch(PDO::FETCH_ASSOC)){
                                            $spin = str_replace('=','',base64_encode($row['support_pin']));

                                            if(is_null($row['support_pin'])) {
                                                $s_pin = $user->renewSupportPin($row['support_pin']);
                                                header('reload:0.15');
                                            }
                                            ?>
                                            <tr>
                                                <th scope="row"><?= $row['id']; ?></th>
                                                <td><?= $row['username']; ?></td>
                                                <td><?= $row['email']; ?></td>
                                                <td><?= $row['amount']; ?>€</td>
                                                <td><?= $helper->formatDate($row['created_at']); ?></td>
                                                <td class="ticket-button"><a href="<?= $helper->url(); ?>team/user/<?= $spin; ?>/" class="btn btn-outline-primary btn-sm font-weight-bolder"><i class="fas fa-eye"></i> Anschauen</a></td>
                                            </tr>
                                        <?php } } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="allmails" tabindex="-1" role="dialog" aria-labelledby="allmails" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">➜ E-Mails abrufen</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table id="my_table3" class="table table-nowrap">
                        <tbody>
                        <?php
                        $SQL = $db -> prepare("SELECT * FROM `customers` ORDER BY `id` DESC");
                        $SQL->execute();
                        if ($SQL->rowCount() != 0) {
                            while ($row = $SQL -> fetch(PDO::FETCH_ASSOC)){?>
                                <tr>
                                    <td><?php echo $row['email']; ?></td>
                                </tr>
                            <?php } } ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal"><i class="fas fa-times"></i> Schließen</button>
            </div>
        </div>
    </div>
</div>