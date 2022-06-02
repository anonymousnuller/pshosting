<?php
$currPage = 'team_Tickets';
include BASE_PATH.'software/controller/PageController.php';
?>
<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
    <div class="post d-flex flex-column-fluid" id="kt_post">
        <div id="kt_content_container" class="container-xxl">
            <div class="container">

                <div class="row">
                    <div class="col-md-12">
                        <div class="card card-custom card-stretch gutter-b">
                            <div class="card-body d-flex flex-column">
                                <table class="table" id="table1">
                                    <thead>
                                    <tr>
                                        <th scope="col">
                                            #
                                        </th>
                                        <th scope="col">
                                            Betreff
                                        </th>
                                        <th scope="col">
                                            Benutzername
                                        </th>
                                        <th scope="col">
                                            Abteilung
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
                                            Erstellt am
                                        </th>
                                        <th scope="col">

                                        </th>
                                    </tr>
                                    </thead>
                                    <tbody class="list">
                                    <?php
                                    $SQL = $db -> prepare("SELECT * FROM `support_tickets` WHERE (`state` in ('OPEN', 'PROCESSING', 'WAITINGC', 'WAITINGI')) ORDER BY `id` DESC");
                                    $SQL->execute();
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
                                                $last_msg = '<span class="badge badge-primary">Kunden-Antwort</span>';
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
                                            } else {
                                                $priority = 'ASAP / Notfall';
                                            }

                                            ?>
                                            <tr>
                                                <th scope="row"><?= $row['id']; ?></th>
                                                <td><?= $helper->xssFix($row['title']); ?></td>
                                                <td><?= $user->getDataById($row['user_id'],'username'); ?></td>
                                                <td><?= ucfirst(strtolower($row['categorie'])); ?></td>
                                                <td><?= $priority; ?></td>
                                                <td><?= $status; ?></td>
                                                <td><?= $last_msg; ?></td>
                                                <td><?= $helper->formatDate($row['created_at']); ?></td>
                                                <td><a href="<?= $helper->url(); ?>team/ticket/<?= $row['id']; ?>/" class="btn btn-outline-primary btn-sm"><i class="fas fa-eye"></i> Anschauen</a></td>
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