<?php

$username = $payload->data->username;
$email = $payload->data->email;
$userid = $payload->data->user_id;
$db_price = $payload->data->price;
$runtime = $payload->data->runtime;
$planName = $payload->data->planName;
$domainName = $payload->data->domainName;
$node_id = $payload->data->node_id;

// generate random password
$password = $helper->generateRandomString('25');

// check if user have an plesk-uid
$SQL = $db->prepare("SELECT * FROM `webspaces` WHERE `user_id` = :user_id AND `state` = :state ORDER BY `id` DESC");
$SQL->execute(array(":user_id" => $userid, ":state" => 'active'));
if($SQL->rowCount() != 0) {
    $row = $SQL->fetch(PDO::FETCH_ASSOC);

    // check if plesk_uid is numeric
    if(!is_numeric($row['plesk_uid'])) {
        $plesk_uid = $plesk->createUser($username, $username, $password, $email);
    } else {
        $plesk_uid = $row['plesk_uid'];
    }

    // await sleep to check the plesk uid
    sleep(1);

    $date = new DateTime(null, new DateTime('Europe/Berlin'));
    $date->modify('+' . $runtime . ' day');
    $expire_at = $date->format('Y-m-d H:i:s');

    $ftp_username = strtolower('ftp_' . $username . rand(0, 9) . '_' . $plesk->getLast());
    $webspace_id = $plesk->create($domainName, $plesk->getHosts()['ip'], $plesk_uid, $ftp_username, $password, $planName);

    if(is_numeric($webspace_id)){
        $insert = $db->prepare("INSERT INTO `webspaces`(`plan_id`, `user_id`, `node_id`, `ftp_name`, `ftp_password`, `plesk_uid`, `plesk_password`, `domainName`, `webspace_id`, `state`, `expire_at`, `price`) VALUES (?,?,?,?,?,?,?,?,?,?,?,?)");
        $insert->execute(array($planName, $userid, $plesk->getHosts()['node_id'], $ftp_username, $password, $plesk_uid, $password, $domainName, $webspace_id, 'active', $expire_at, $db_price));
    } else {
        $update = $db->prepare("UPDATE `queue` SET `retries` = :retries, `error_log` = :error_log WHERE `id` = :id");
        $update->execute(array(":retries" => '255', ":error_log" => $webspace_id, ":id" => $row['id']));
        die('error happend 1 <br>' . $webspace_id);
    }

} else {
    $plesk_uid = $plesk->createUser($username, $username, $password, $email);

    $date = new DateTime(null, new DateTimeZone('Europe/Berlin'));
    $date->modify('+'.$runtime.' day');
    $expire_at = $date->format('Y-m-d H:i:s');

    //$domainName = 'web'.$plesk->getLast().rand(0,9).'-'.rand(0,9).'.'.$plesk->getHost()['domainName'];
    $ftp_username = strtolower('ftp_' . $username . rand(0, 9) . '_' . $plesk->getLast());
    $webspace_id = $plesk->create($domainName, $plesk->getHosts()['ip'], $plesk_uid, $ftp_username, $password, $planName);

    if(is_numeric($webspace_id)){
        $insert = $db->prepare("INSERT INTO `webspaces`(`plan_id`, `user_id`, `node_id`, `ftp_name`, `ftp_password`, `plesk_uid`, `plesk_password`, `domainName`, `webspace_id`, `state`, `expire_at`, `price`) VALUES (?,?,?,?,?,?,?,?,?,?,?,?)");
        $insert->execute(array($planName, $userid, $plesk->getHosts()['node_id'], $ftp_username, $password, $plesk_uid, $password, $domainName, $webspace_id, 'active', $expire_at, $db_price));
    } else {
        $update = $db->prepare("UPDATE `queue` SET `retries` = :retries, `error_log` = :error_log WHERE `id` = :id");
        $update->execute(array(":retries" => '255', ":error_log" => $webspace_id, ":id" => $row['id']));
        die('error happend 1 <br>' . $webspace_id);
    }
}