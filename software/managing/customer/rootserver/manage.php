<?php

$id = $helper->protect($_GET['id']);

$SQLGetServerInfos = $db->prepare("SELECT * FROM `kvm_servers` WHERE `id` = :id");
$SQLGetServerInfos -> execute(array(":id" => $id));
$serverInfos = $SQLGetServerInfos -> fetch(PDO::FETCH_ASSOC);

if(!($serverInfos['deleted_at'] == NULL)){
    header('Location: '.$helper->url().'order/rootserver/');
    die();
}

if(!is_null($serverInfos['locked'])){
    $_SESSION['product_locked_msg'] = $serverInfos['locked'];
    header('Location: '.env('URL').'index/rootserver/');
    die();
}

if(is_null($serverInfos['traffic'])){
    $available_traffic = $helper->getSetting('default_traffic_limit');
} else {
    $available_traffic = $serverInfos['traffic'];
}

if($serverInfos['state'] == 'SUSPENDED'){
    $suspended = true;
    die(header('Location: '.$helper->url().'renew/rootserver/'.$id. '/'));
} else {
    $suspended = false;
}

if($userid != $serverInfos['user_id']){
    die(header('Location: '.$helper->url().'index/rootserver/'));
}

$status = $kvm->getStatus($serverInfos['node_id'], $serverInfos['id']);
$status = json_decode($status);

if($status->data->status == 'running'){
    $state = '<span class="badge badge-success">Online</span>';
    $serverStatus = 'ONLINE';
} else {
    $serverStatus = 'OFFLINE';
    $state = '<span class="badge badge-danger">Offline</span>';
}

if (isset($_POST['sendStop'])) {
    $error = null;

    if ($status->data->status == 'stopped') {
        $error = 'Dein Server ist bereits gestoppt';
    }

    if (empty($error)) {

        $serverStatus = 'OFFLINE';
        $kvm->stopServer($serverInfos['node_id'], $serverInfos['id']);
        echo sendSweetSuccess('Dein Server wird nun gestoppt');

    } else {
        echo sendError($error);
    }
}

if (isset($_POST['sendStart'])) {
    $error = null;

    if ($status->data->status == 'running') {
        $error = 'Dein Server ist bereits gestartet';
    }

    if (empty($error)) {

        $serverStatus = 'ONLINE';
        $kvm->startServer($serverInfos['node_id'], $serverInfos['id']);
        echo sendSweetSuccess('Dein Server wird nun gestartet');

    } else {
        echo sendError($error);
    }
}

if (isset($_POST['sendRestart'])) {
    $error = null;

    if ($status->data->status == 'stopped') {
        $error = 'Dein Server ist bereits gestoppt';
    }

    if (empty($error)) {

        $serverStatus = 'ONLINE';
        $kvm->stopServer($serverInfos['node_id'], $serverInfos['id']);
        sleep(3);
        $kvm->startServer($serverInfos['node_id'], $serverInfos['id']);
        echo sendSweetSuccess('Dein Server wurde nun neugestartet');

    } else {
        echo sendError($error);
    }
}

if(isset($_POST['resetPassword'])) {
    $error = null;

    if(empty($error)) {
        // generate new password
        $password = $helper->generateRandomString('20');

        $getCredentials = $db->prepare("SELECT * FROM `kvm_servers_nodes` WHERE `id` = :id ORDER BY `id` DESC LIMIT 1;");
        $getCredentials->execute(array(":id" => $serverInfos['node_id']));
        $credentials = $getCredentials->fetch(PDO::FETCH_ASSOC);

        $task = $kvm->exec('qm set ' . $id . ' --cipassword "' . $password . '"', $credentials);

        $kvm->stopServer($serverInfos['node_id'], $id);
        sleep(1);
        $kvm->startServer($serverInfos['node_id'], $id);

        $SQL4 = $db->prepare("INSERT INTO `kvm_servers_tasks`(`service_id`, `task`) VALUES (:service_id, :task)");
        $SQL4->execute(array(":service_id" => $id, ":task" => $task));

        $SQL = $db->prepare("UPDATE `kvm_servers` SET `password` = :password WHERE `id` = :id");
        $SQL->execute(array(":password" => $password, ":id" => $id));

        echo sendSweetSuccess('Root-Passwort wurde zurückgesetzt.');
        header('Refresh: 0.5');
    } else {
        echo sendError($error);
    }
}

if(isset($_POST['reinstallServer'])) {
    $error = null;

    if(empty($_POST['serverOS'])) {
        $error = 'Kein Betriebssystem gefunden.';
    }

    if($site->validateRootserverOS($_POST['serverOS']) == false){
        $error = 'serverOS does not exists';
    }

    if(empty($error)) {

        if($serverStatus == 'ONLINE') {
            $kvm->stopServer($serverInfos['node_id'], $id);
        }

        $SQL = $db->prepare("SELECT * FROM `kvm_servers_os` WHERE `id` = :id");
        $SQL->execute(array(":id" => $_POST['serverOS']));
        $response = $SQL->fetch(PDO::FETCH_ASSOC);
        $serverOS = $response['prox_id'];

        // generate new password
        $password = $helper->generateRandomString('20');
        $nameserver = '1.1.1.1,1.0.0.1';

        $getCredentials = $db->prepare("SELECT * FROM `kvm_servers_nodes` WHERE `id` = :id ORDER BY `id` DESC LIMIT 1;");
        $getCredentials->execute(array(":id" => $serverInfos['node_id']));
        $credentials = $getCredentials->fetch(PDO::FETCH_ASSOC);

        $task = $kvm->exec('qm destroy ' . $id . ' && qm clone ' . $serverOS . ' ' . $id . ' --name ' . $serverInfos['hostname'] . ' && qm set ' . $id . ' --cipassword "' . $password . '" && qm set ' . $id . ' --ciuser root && qm set ' . $id . ' --ipconfig0 ip=' . $site->getMainAddressFromServer($id, 'ip') . '/' . $site->getMainAddressFromServer($id, 'cidr') . ',gw=' . $site->getMainAddressFromServer($id, 'gateway'). ' && qm set ' . $id . ' --nameserver="' . $nameserver . '" && qm set ' . $new_vm_id . ' --net0 virtio="' . $site->getMainAddressFromServer($id, 'mac_address') . '",bridge=vmbr0,rate=30', $credentials);
        $SQL4 = $db->prepare("INSERT INTO `kvm_servers_tasks`(`service_id`, `task`) VALUES (:service_id, :task)");
        $SQL4->execute(array(":service_id" => $id, ":task" => $task));

        $SQL = $db->prepare("UPDATE `kvm_servers` SET `template_id` = :template_id WHERE `id` = :id");
        $SQL->execute(array(":template_id" => $_POST['serverOS'], ":id" => $id));

        // check hardware if correct set
        $kvm->correctCores($credentials['id'], $id, $serverInfos['cores']);
        $kvm->correctDisk($credentials['id'], $id, $serverInfos['disc']);
        $kvm->correctMemory($credentials['id'], $id, $serverInfos['memory']);

        sleep(1);

        $kvm->startServer($credentials['id'], $id);

        echo sendSweetSuccess('Server wird nun neuinstalliert.');
        header('Refresh: 0.5');
    } else {
        echo sendError($error);
    }
}