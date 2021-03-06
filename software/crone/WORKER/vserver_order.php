<?php

$username = $payload->data->username;
$email = $payload->data->email;
$userid = $row['user_id'];
$db_price = $payload->data->price;
$runtime = $payload->data->runtime;

$rootpassword = $payload->data->rootpassword;
$hostname = $payload->data->hostname;
$serverOS = $payload->data->serverOS;

$cores = $payload->data->cores;
$memory = $payload->data->memory;
$disk = $payload->data->disk;
$ip_addrs = $payload->data->ip_addrs;
$addresses = $payload->data->addresses;

$node_id = $payload->data->node_id;
$disc_name = $payload->data->disc_name;
$api_name = $payload->data->api_name;
$traffic = $payload->data->traffic;
$pack_name = $payload->data->pack_name;
$network = $payload->data->network;

//dd($ip_addrs);

$serviceID = $site->getLastLXCID();
$task = $lxc->create($node_id, $serviceID, $serverOS, $cores, $memory, $rootpassword, $disk,'512', $ip_addrs, $addresses, $hostname, $disc_name, $network);
$SQL4 = $db->prepare("INSERT INTO `lxc_servers_tasks`(`service_id`, `task`) VALUES (:service_id, :task)");
$SQL4->execute(array(":service_id" => $serviceID, ":task" => $task));

$lxc->startServer($node_id, $serviceID);

$date = new DateTime(null, new DateTimeZone('Europe/Berlin'));
$date->modify('+' . $runtime . ' day');
$expire_at = $date->format('Y-m-d H:i:s');

$SQLDB = $db;
$SQL5 = $SQLDB->prepare("INSERT INTO `lxc_servers`(`user_id`, `hostname`, `password`, `template_id`, `node_id`, `cores`, `memory`, `disc`, `addresses`, `price`, `state`, `expire_at`, `disc_name`, `traffic`, `api_name`, `pack_name`, `network`) VALUES (:user_id,:hostname,:password,:template_id,:node_id,:cores,:memory,:disc,:addresses,:price,:state,:expire_at,:disc_name,:traffic,:api_name,:pack_name,:network)");
$SQL5->execute(array(":user_id" => $userid, ":hostname" => $hostname, ":password" => $rootpassword, ":template_id" => $serverOS, ":node_id" => $node_id, ":cores" => $cores, ":memory" => $memory, ":disc" => $disk, ":addresses" => $addresses, ":price" => $db_price, ":state" => 'ACTIVE', ":expire_at" => $expire_at, ":disc_name" => $disc_name, ":traffic" => $traffic, ":api_name" => $api_name, ":pack_name" => $pack_name, ":network" => $network));