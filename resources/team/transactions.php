<?php
/*
 *   Copyright (c) 2021 Bastian Leicht
 *   All rights reserved.
 *   https://bastianleicht.com/license
 */

$currPage = 'team_Transaktionen';
include BASE_PATH.'software/controller/PageController.php';

$text = 'Bitte auswählen';
if($_POST['type'] == 'all'){
    $amount = 0;

    $SQL = $db->prepare("SELECT * FROM `customers_charge_transactions` WHERE `state` = 'success' AND `gateway` = 'Mollie' || 'PayPal'");
    $SQL->execute();
    if ($SQL->rowCount() != 0) {
        while ($row = $SQL->fetch(PDO::FETCH_ASSOC)) {
            $amount = $amount + $row['amount'];
        }
    }

    $text = 'Die Gesamteinnahmen betragen '.number_format($amount,2).'€';
}

if($_POST['type'] == 'year'){
    $amount = 0;

    $dateMinus = new DateTime(null, new DateTimeZone('Europe/Berlin'));
    $dateMinus->modify('-365 day');
    $dateTimeMinus = $dateMinus->format('Y-m-d H:i:s');

    $SQL = $db->prepare("SELECT * FROM `customers_charge_transactions` WHERE `state` = 'success' AND `created_at` > :dateTimeMinus AND `gateway` = 'System' IS NULL");
    $SQL->execute(array(":dateTimeMinus" => $dateTimeMinus));
    if ($SQL->rowCount() != 0) {
        while ($row = $SQL->fetch(PDO::FETCH_ASSOC)) {
            $amount = $amount + $row['amount'];
        }
    }

    $text = 'Die Einnahmen von den letzten 365 Tagen betragen '.number_format($amount,2).'€';
}

if($_POST['type'] == 'month'){
    $amount = 0;

    $dateMinus = new DateTime(null, new DateTimeZone('Europe/Berlin'));
    $dateMinus->modify('-30 day');
    $dateTimeMinus = $dateMinus->format('Y-m-d H:i:s');

    $SQL = $db->prepare("SELECT * FROM `customers_charge_customers_charge_transactions` WHERE `state` = 'success' AND `created_at` > :dateTimeMinus AND `gateway` = 'System' IS NULL");
    $SQL->execute(array(":dateTimeMinus" => $dateTimeMinus));
    if ($SQL->rowCount() != 0) {
        while ($row = $SQL->fetch(PDO::FETCH_ASSOC)) {
            $amount = $amount + $row['amount'];
        }
    }

    $text = 'Die Einnahmen von den letzten 30 Tagen betragen '.number_format($amount,2).'€';
}

if($_POST['type'] == 'day'){
    $amount = 0;

    $dateMinus = new DateTime(null, new DateTimeZone('Europe/Berlin'));
    $dateMinus->modify('-1 day');
    $dateTimeMinus = $dateMinus->format('Y-m-d H:i:s');

    $SQL = $db->prepare("SELECT * FROM `customers_charge_transactions` WHERE `state` = 'success' AND `created_at` > :dateTimeMinus AND `gateway` = 'System' IS NULL");
    $SQL->execute(array(":dateTimeMinus" => $dateTimeMinus));
    if ($SQL->rowCount() != 0) {
        while ($row = $SQL->fetch(PDO::FETCH_ASSOC)) {
            $amount = $amount + $row['amount'];
        }
    }

    $text = 'Die Einnahmen von den letzten 24 Stunden betragen '.number_format($amount,2).'€';
}

?>
<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
    <div class="post d-flex flex-column-fluid" id="kt_post">

        <div id="kt_content_container" class="container-xxl">
            <div class="container">
                <div class="row">

                    <div class="col-md-6">
                        <div class="card card-custom card-stretch gutter-b">
                            <div class="card-body d-flex flex-column">

                                <form method="post">
                                    <input value="all" name="type" hidden>
                                    <button type="submit" class="btn btn-primary btn-block">Gesamt</button>
                                </form>

                                <br>

                                <form method="post">
                                    <input value="year" name="type" hidden>
                                    <button type="submit" class="btn btn-primary btn-block">1 Jahr</button>
                                </form>

                                <br>

                                <form method="post">
                                    <input value="month" name="type" hidden>
                                    <button type="submit" class="btn btn-primary btn-block">Monat</button>
                                </form>

                                <br>

                                <form method="post">
                                    <input value="day" name="type" hidden>
                                    <button type="submit" class="btn btn-primary btn-block">Tag</button>
                                </form>

                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="card card-custom card-stretch gutter-b">
                            <div class="card-body d-flex flex-column">

                                <?= $text; ?>

                            </div>
                        </div>
                    </div>

                    <div class="col-md-12">

                        <br>

                        <div class="card card-custom card-stretch gutter-b">
                            <div class="card-body d-flex flex-column">
                                <table id="table1" class="table dt-responsive nowrap">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Beschreibung</th>
                                        <th>Betrag</th>
                                        <th>Transaktions-ID</th>
                                        <th>Datum</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    $SQL = $db->prepare("SELECT * FROM `customers_charge_transactions` WHERE `state` = 'success'");
                                    $SQL->execute();
                                    if ($SQL->rowCount() != 0) {
                                        while ($row = $SQL -> fetch(PDO::FETCH_ASSOC)){?>
                                            <tr>
                                                <td><?= $row['id']; ?></td>
                                                <td><?= $row['desc']; ?></td>
                                                <td><?= $row['amount']; ?>€</td>
                                                <td><?= $row['tid']; ?></td>
                                                <td><?= $helper->formatDate($row['created_at']); ?></td>
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