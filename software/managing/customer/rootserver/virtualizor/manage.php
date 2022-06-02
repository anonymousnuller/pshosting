<?php

$id = $helper->protect($_GET['id']);

$SQL = $db->prepare("SELECT * FROM `kvm_servers` WHERE `id` = :id");
$SQL->execute(array(":id" => $id));
$serverInfos = $SQL->fetch(PDO::FETCH_ASSOC);

$vid = $serverInfos['virtualizor_id'];

if(!is_null($serverInfos['deleted_at'])) {
    header('Location: ' . env('URL') . '');
}

if($serverInfos['state'] == 'suspended') {
    $suspended = true;
} else {
    $suspended = false;
}

if($userid != $serverInfos['user_id']) {
    die(header('Location: ' . env('URL') . ''));
}

if($serverInfos['state'] == 'active') {

} elseif($serverInfos['state'] == 'suspended') {

} elseif($serverInfos['state'] == 'deleted') {

}

$hostname = $serverInfos['hostname'];
if(isset($_POST['changeHostname'])) {
    $error = null;

    if(empty($_POST['hostname'])) {
        $error = 'Bitte gib einen Hostname an.';
    }

    if(empty($error)) {

        $SQL = $db->prepare("UPDATE `kvm_servers` SET `hostname` = :hostname WHERE `id` = :id");
        $SQL->execute(array(":hostname" => $_POST['hostname'], ":id" => $id));

        $hostname = $_POST['hostname'];

        echo sendSuccess('Hostname wurde geändert.');
    } else {
        echo sendError($error);
    }
}


