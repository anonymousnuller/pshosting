<?php

$username = $payload->data->username;
$email = $payload->data->email;
$userid = $row['user_id'];
$db_price = $payload->data->price;
$runtime = $payload->data->runtime;

$password = $payload->data->root_password;
$hostname = $payload->data->hostname;
$serverOS = $payload->data->serverOS;

$cores = $payload->data->cores;
$memory = $payload->data->memory;
$disk = $payload->data->disk;
$ip_addr = $payload->data->ip_addr;
$addresses = $payload->data->addresses;
$nameserver = '1.1.1.1,1.0.0.1';

$node_id = $payload->data->node_id;
$disc_name = $payload->data->disc_name;
$api_name = $payload->data->api_name;
$traffic = $payload->data->traffic;
$pack_name = $payload->data->pack_name;

$new_vm_id = $site->getLastKVMID();

$SQL = $db->prepare("SELECT * FROM `ipv4_pool` WHERE `service_id` IS NULL AND `node_id` = :node_id ORDER BY `id` ASC LIMIT 1;");
$SQL->execute(array(":node_id" => $node_id));
if($SQL->rowCount() == 1) {
    $serverAddr = $SQL->fetch(PDO::FETCH_ASSOC);

    $SQL = $db->prepare("UPDATE `ipv4_pool` SET `service_id` = :service_id WHERE `id` = :id");
    $SQL->execute(array(":service_id" => $new_vm_id, ":id" => $serverAddr['id']));

    $getCredentials = $db->prepare("SELECT * FROM `kvm_servers_nodes` WHERE `id` = :id ORDER BY `id` DESC LIMIT 1;");
    $getCredentials->execute(array(":id" => $node_id));
    $credentials = $getCredentials->fetch(PDO::FETCH_ASSOC);

    $task = $kvm->exec('qm clone ' . $serverOS . ' ' . $new_vm_id . ' --name ' . $hostname . ' && qm set ' . $new_vm_id . ' --cipassword "' . $password . '" && qm set ' . $new_vm_id . ' --ciuser root && qm set ' . $new_vm_id . ' --ipconfig0 ip=' . $serverAddr['ip'] . '/' . $serverAddr['cidr'] . ',gw=' . $serverAddr['gateway'] . ' && qm set ' . $new_vm_id . ' --nameserver="' . $nameserver . '" && qm set ' . $new_vm_id . ' --net0 virtio="' . $serverAddr['mac_address'] . '",bridge=vmbr0,rate=30', $credentials);
    $SQL4 = $db->prepare("INSERT INTO `kvm_servers_tasks`(`service_id`, `task`) VALUES (:service_id, :task)");
    $SQL4->execute(array(":service_id" => $new_vm_id, ":task" => $task));

    // check hardware if correct set
    $kvm->correctCores($credentials['id'], $new_vm_id, $cores);
    $kvm->correctDisk($credentials['id'], $new_vm_id, $disk);
    $kvm->correctMemory($credentials['id'], $new_vm_id, $memory);

    sleep(1);

    $kvm->startServer($credentials['id'], $new_vm_id);

    // set date
    $date = new DateTime(null, new DateTimeZone('Europe/Berlin'));
    $date->modify('+' . $runtime . ' day');
    $expire_at = $date->format('Y-m-d H:i:s');

    $SQLDB = $db;
    $SQL5 = $SQLDB->prepare("INSERT INTO `kvm_servers`(`user_id`, `hostname`, `password`, `template_id`, `node_id`, `cores`, `memory`, `disc`, `addresses`, `curr_traffic`, `traffic`, `price`, `state`, `expire_at`, `pack_name`) VALUES (:user_id,:hostname,:password,:template_id,:node_id,:cores,:memory,:disc,:addresses,:curr_traffic,:traffic,:price,:state,:expire_at,:pack_name)");
    $SQL5->execute(array(":user_id" => $userid, ":hostname" => $hostname, ":password" => $password, ":template_id" => $serverOS, ":node_id" => $node_id, ":cores" => $cores, ":memory" => $memory, ":disc" => $disk, ":addresses" => $addresses, ":curr_traffic" => '0', ":traffic" => $helper->getSetting('default_traffic_limit'), ":price" => $db_price, ":state" => 'ACTIVE', ":expire_at" => $expire_at, ":pack_name" => $pack_name));

} else {
    $update = $db->prepare("UPDATE `queue` SET `retries` = :retries, `error_log` = :error_log WHERE `id` = :id");
    $update->execute(array(":retries" => '255', ":error_log" => 'no ips available', ":id" => $row['id']));
    die('error happened');
}