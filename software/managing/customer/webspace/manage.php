<?php

$id = $helper->protect($_GET['id']);

$SQLGetServerInfos = $db->prepare("SELECT * FROM `webspaces` WHERE `id` = :id");
$SQLGetServerInfos -> execute(array(":id" => $id));
$serverInfos = $SQLGetServerInfos -> fetch(PDO::FETCH_ASSOC);

if(!is_null($serverInfos['locked'])){
    $_SESSION['product_locked_msg'] = $serverInfos['locked'];
    header('Location: '.env('URL').'manage/webspaces/');
    die();
}

$SQL = $db->prepare("SELECT * FROM `webspaces_hosts` WHERE `node_id` = :node_id");
$SQL -> execute(array(":node_id" => $serverInfos['node_id']));
$webhostInfos = $SQL -> fetch(PDO::FETCH_ASSOC);

if(!($serverInfos['deleted_at'] == NULL)){
    header('Location: '.$helper->url().'order/webspace/');
}

if($serverInfos['state'] == 'suspended'){
    $suspended = true;
} else {
    $suspended = false;
}

if($userid != $serverInfos['user_id']){
    die(header('Location: '.$helper->url().'manage/webspaces/'));
}

if(isset($_POST['login'])){
    echo '<script type="text/javascript" language="Javascript">window.open("'.$plesk->generateSession($username.$userid, $user->getIP(), $webhostInfos['url']).'");</script>';
}