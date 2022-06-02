<?php
$currPage = 'system_worker queue';
include BASE_PATH.'software/controller/PageController.php';

$server_id = $helper->protect($_GET['id']);

if(isset($_GET['id'])){
    $SQLGetServerInfos = $db->prepare("SELECT * FROM `lxc_servers` WHERE `id` = :id");
    $SQLGetServerInfos -> execute(array(":id" => $server_id));
    $serverInfos = $SQLGetServerInfos -> fetch(PDO::FETCH_ASSOC);

    echo $lxc->getStatus($serverInfos['node_id'], $serverInfos['id']);
} else {
    echo 'ERROR';
}