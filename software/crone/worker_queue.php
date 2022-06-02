<?php
$currPage = 'system_worker queue';
include BASE_PATH . 'software/controller/PageController.php';

$key = $helper->protect($_GET['key']);

if($key == env('CRONE_KEY')) {

    $SQL = $db->prepare("SELECT * FROM `queue` WHERE `retries` = '0'");
    $SQL->execute();
    if($SQL->rowCount() != 0) {
        while($row = $SQL->fetch(PDO::FETCH_ASSOC)) {
            $error = null;

            $payload = json_decode($row['payload']);

            if($payload->action == 'ROOTSERVER_ORDER') {
                include BASE_PATH . 'software/crone/WORKER/rootserver_order.php';
                $worker->success($row['id']);

                die('worker success rootserver with id ' . $row['id']);
            }

            if($payload->action == 'VSERVER_ORDER') {
                include BASE_PATH . 'software/crone/WORKER/vserver_order.php';
                $worker->success($row['id']);

                die('worker success vserver with id ' . $row['id']);
            }

            if($payload->action == 'WEBSPACE_ORDER') {
                include BASE_PATH . 'software/crone/WORKER/webspace_order.php';
                $worker->success($row['id']);

                die('worker success webspace with id ' . $row['id']);
            }

            if($payload->action == 'INSTALLER') {
                include BASE_PATH . 'software/crone/WORKER/installer.php';
                $worker->success($row['id']);

                die('worker success installer with id ' . $row['id']);
            }
        }
    }

}