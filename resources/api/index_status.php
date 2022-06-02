<?php

error_reporting('E_STRICT');
header("Access-Control-Allow-Origin: *");
header('Content-Type: application/json; charset=utf-8');

$currPage = 'system_GlobalAPI';
include BASE_PATH.'software/controller/PageController.php';


/* ---------------------------------------------------- */
$warning = array();
$error = array();
$success = array();
$required = array();
/* ---------------------------------------------------- */

$res = new stdClass();
$res->metadata->clientRequestId = null;
$res->metadata->serverRequestId = null;
$res->metadata->serverRequestTime = $date;



    if($_GET['action'] == 'getInfo') {
        include BASE_PATH . 'resources/api/status/getInfo.php';
    } elseif($_GET['action'] == 'getAlerts') {
        include BASE_PATH . 'resources/api/status/alerts.php';
    } else {
        array_push($error, 'Aktion wurde nicht übergeben');
        $state = 'error';
        $required = 'action';
    }


$res->state = $state;
$res->status_code = $status_code;

if(empty($res->data)){
    $res->data = array();
}

if(!empty($required)){
    $res->data->required = $required;
}

$res->message->warning = $warning;
$res->message->error = $error;
$res->message->success = $success;
if(!empty($debug)){
    $res->message->debug = $debug;
}
$res = json_encode($res);
die($res);