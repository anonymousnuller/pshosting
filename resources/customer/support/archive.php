<?php

$currPage = 'customer_Support-Ticket Archiv';
include BASE_PATH . 'software/controller/PageController.php';

?>

<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
    <div class="subheader py-2 py-lg-4 subheader-solid" id="kt_subheader">
        <div class="container-fluid d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
            <div class="d-flex align-items-center flex-wrap mr-2">
                <h5 class="text-dark font-weight-bold mt-2 mb-2 mr-5"><?= env('APP_NAME'); ?></h5>
                <div class="subheader-separator subheader-separator-ver mt-2 mb-2 mr-4 bg-gray-200"></div>
                <span class="text-muted font-weight-bold mr-4"><?= $currPageName; ?></span>
            </div>
        </div>
    </div>

    <div class="d-flex flex-column-fluid">
        <div class="container">

            <div class="row">
                <div class="col-md-12">
                    <div class="card card-custom card-stretch gutter-b shadow mb-5">
                        <div class="card-body d-flex flex-column">
                            <table class="table" id="dataTableLoad">
                                <thead>
                                <tr>
                                    <th scope="col">
                                        #
                                    </th>
                                    <th scope="col">
                                        Betreff
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
                                $SQL = $db -> prepare("SELECT * FROM `support_tickets` WHERE `user_id` = :user_id AND `state` = :state ORDER BY `id` DESC");
                                $SQL->execute(array(":user_id" => $userid, ":state" => 'CLOSED'));
                                if ($SQL->rowCount() != 0) {
                                    while ($row = $SQL -> fetch(PDO::FETCH_ASSOC)){

                                        if($row['state'] == 'CLOSED'){
                                            $status = '<span class="badge badge-danger">Geschlossen</span>';
                                        }

                                        if($row['last_msg'] == 'CUSTOMER'){
                                            $last_msg = '<span class="badge badge-secondary">Kunden-Antwort</span>';
                                        } elseif($row['last_msg'] == 'SUPPORT'){
                                            $last_msg = '<span class="badge badge-info">Support-Antwort</span>';
                                        }

                                        if($row['priority'] == 'NIEDRIG'){
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

                                        ?>
                                        <tr>
                                            <th scope="row"><?= $row['id']; ?></th>
                                            <td><?= $helper->xssFix($row['title']); ?></td>
                                            <td><?= ucfirst(strtolower($row['categorie'])); ?></td>
                                            <td><?= $priority; ?></td>
                                            <td><?= $status; ?></td>
                                            <td><?= $last_msg; ?></td>
                                            <td><?= $helper->formatDate($row['created_at']); ?></td>
                                            <td><a href="<?= $helper->url(); ?>support/ticket/<?= $row['id']; ?>/" class="btn btn-outline-primary btn-sm font-weight-bolder"><i class="fas fa-eye"></i> Anschauen</a></td>
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
