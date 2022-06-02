<?php

$id = $helper->protect($_GET['id']);

$SQLGetServerInfos = $db->prepare("SELECT * FROM `services` WHERE `id` = :id");
$SQLGetServerInfos -> execute(array(":id" => $id));
$serverInfos = $SQLGetServerInfos -> fetch(PDO::FETCH_ASSOC);

if(!($serverInfos['deleted_at'] == NULL)){
    header('Location: '.$helper->url().'index/');
}

if(!is_null($serverInfos['locked'])){
    $_SESSION['product_locked_msg'] = $serverInfos['locked'];
    header('Location: '.env('URL').'index/services/');
    die();
}

if($serverInfos['state'] == 'active'){
    $status_msg = '<span class="badge badge-success">Aktiv</span>';
} elseif($serverInfos['state'] == 'pending') {
    $status_msg = '<span class="badge badge-warning">Warte auf Freischaltung</span>';
} elseif($serverInfos['state'] == 'suspended') {
    $status_msg = '<span class="badge badge-danger">Gesperrt</span>';
} else {
    $status_msg = '<span class="badge badge-info">Kein Status gefunden</span>';
}

if($userid != $serverInfos['user_id']){
    die(header('Location: '.$helper->url().'index/'));
}