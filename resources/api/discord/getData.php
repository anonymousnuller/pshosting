<?php
array_push($success, 'Daten wurden abgefragt');
$state = 'success';

$support_pin = $helper->protect($_POST['support_pin']);

$SQL = $db->prepare("SELECT * FROM `customers` WHERE `support_pin` = :s_pin");
$SQL->execute(array(":s_pin" => $support_pin));
$userInfos = $SQL->fetch(PDO::FETCH_ASSOC);

$res->data->username = $userInfos['username'];
$res->data->email = $userInfos['email'];
$res->data->state = $userInfos['state'];
$res->data->amount = $userInfos['amount'];
$res->data->firstname = $userInfos['firstname'];
$res->data->lastname = $userInfos['lastname'];
$res->data->discord_id = $userInfos['discord_id'];