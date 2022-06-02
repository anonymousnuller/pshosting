<?php

error_reporting('E_STRICT');
header("Access-Control-Allow-Origin: *");
header('Content-Type: application/json; charset=utf-8');

$currPage = 'system_GlobalAPI_hidelayout';
include BASE . 'software/controller/PageController.php';

$authToken = $_SERVER['HTTP_X_AUTH_TOKEN'];

/* ---------------------------------------------------- */
$warning = array();
$error = array();
$success = array();
$required = array();
/* ---------------------------------------------------- */

$res = new stdClass();


if($api->validateLiveKey($authToken)){

    $SQL = $db->prepare("SELECT * FROM `api_keys` WHERE `api_key` = :api_key AND `state` = 'active'");
    $SQL->execute(array(":api_key" => $authToken));
    $keyinfos = $SQL->fetch(PDO::FETCH_ASSOC);

    $clientid = $keyinfos['user_id'];
    $serverid = $site->generateNumber();

    $res->metadata->clientRequestId = $keyinfos['user_id'];
    $res->metadata->serverRequestId = $serverid;

    if($_GET['action'] == 'getAll'){
        include BASE.'resources/api/handles/getAll.php';
    } elseif($_GET['action'] == 'get') {
        include BASE . 'resources/api/handles/get.php';
    } elseif($_GET['action'] == 'create') {
        include BASE . 'resources/api/handles/create.php';
    } elseif($_GET['action'] == 'update') {
        include BASE . 'resources/api/handles/update.php';
    } elseif($_GET['action'] == 'delete') {
        include BASE . 'resources/api/handles/delete.php';
    } else {
        array_push($error, 'Diese Funktion existiert nicht.');
        $state = 'error';
        $required = 'action';
    }

} else {
    array_push($error, 'Keine Berechtigung oder ungueltiger API Key.');
    $state = 'error';
}


if(!empty($required)){
    $res->data->required = $required;
}

$res->state = $state;
$res->message->warning = $warning;
$res->message->error = $error;
$res->message->success = $success;

if(!empty($debug)){
    $res->message->debug = $debug;
}

$res = json_encode($res, JSON_FORCE_OBJECT);

if($api->validateLiveKey($authToken)) {
    $type = 'production';

    $LOG = $db->prepare("INSERT INTO `api_logs`(`user_id`, `type`, `response_id`, `api_key`, `log`) VALUES (?, ?, ?, ?, ?)");
    $LOG->execute(array($keyinfos['user_id'], $type, $serverid, $authToken, $res));
}

die($res);