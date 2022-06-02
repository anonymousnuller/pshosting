<?php

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

if(isset($_POST['buyTraffic'])){
    $error = null;

    $traffic_valid = false;
    $traffic_amount = $_POST['traffic_amount'];
    if($traffic_amount == '512' || $traffic_amount == '1024'){
        $traffic_valid = true;
    }
    if($traffic_valid == false){
        $error = 'Diese möglichkeit existiert nicht';
    }


    if($traffic_amount == '512'){
        $price = '7.00';
    }
    if($traffic_amount == '1024'){
        $price = '14.00';
    }

    if($price > $amount){
        $error = 'Du hast nicht genügent Guthaben';
    }

    if(empty($error)){

        $user->removeMoney($price, $userid);
        $user->addOrder($userid, $price,'KVM #'.$id.' | Extra Traffic '.$traffic_amount.'GB');

        $update = $db->prepare("UPDATE `kvm_servers` SET `traffic` = :traffic WHERE `id` = :id");
        $update->execute(array(":traffic" => $available_traffic+$traffic_amount, ":id" => $id));

        $_SESSION['success_msg'] = 'Vielen Dank. Dein Server wird in kürze wieder freigeschaltet!';
        header('Location: '.$site->currentUrl());
        die();

    } else{
        echo sendError($error);
    }
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

$venocix_id = json_decode($serverInfos['hosterapi_id']);
$vm_id = $venocix_id->result->output->vmid;
$status = $hosterapi->getStatus($vm_id);
if($status->result->state == 'running'){
    $state = '<span class="badge badge-success">Online</span>';
    $serverStatus = 'ONLINE';
} else {
    $serverStatus = 'OFFLINE';
    $state = '<span class="badge badge-danger">Offline</span>';
}

if($available_traffic > $serverInfos['curr_traffic']) {

    if (isset($_POST['reinstallServer'])) {
        $error = null;

        if(empty($_POST['serverOS'])){
            $error = 'serverOS not found';
        }

        /*if ($vmsoftware->getOpenInstalls($serverInfos['id'])) {
            $error = 'Es läuft noch eine Installation';
        }*/

        if($site->validateKVMOS($_POST['serverOS'], 'AMD') == false){
            $error = 'serverOS does not exists';
        }

        $SQL = $db->prepare("SELECT * FROM `kvm_servers_os` WHERE `id` = :id AND `type` = :type");
        $SQL->execute(array(":id" => $_POST['serverOS'], ":type" => 'AMD'));
        $response = $SQL->fetch(PDO::FETCH_ASSOC);
        $serverOS = $response['template'];

        if (empty($error)) {

            $serviceID = $vm_id;

            $task = $hosterapi->reinstall($vm_id, $serverOS);
            $SQL = $db->prepare("INSERT INTO `kvm_servers_tasks`(`service_id`, `task`) VALUES (:service_id, :task)");
            $SQL->execute(array(":service_id" => $serviceID, ":task" => json_encode($task)));

            $rootpassword = null;
            $hostname = null;
            $job_id = $task->result->jobId;

            $SQLDB = $db;
            $SQL = $SQLDB->prepare("UPDATE `kvm_servers` SET `hostname` = :hostname, `password` = :password, `template_id` = :template_id, `job_id` = :job_id, `hosterapi_id` = :hosterapi_id WHERE `id` = :id");
            $SQL->execute(array(":template_id" => $serverOS, ":hostname" => $hostname, ":password" => $rootpassword, ":id" => $serverInfos['id'], ":job_id" => $job_id, ":hosterapi_id" => null));

            $serverStatus = 'OFFLINE';

            echo sendSweetSuccess('Dein Server wurde neuinstalliert.');
            header('Location: ' . env('URL') . 'index/rootserver/');
        } else {
            echo sendError($error);
        }

    }

    if (isset($_POST['sendStop'])) {
        $error = null;

        if ($status->result->state == 'stopped') {
            $error = 'Dein Server ist bereits gestoppt';
        }

        if (empty($error)) {

            $serverStatus = 'OFFLINE';
            $hosterapi->normalStop($vm_id);
            echo sendSweetSuccess('Dein Server wird nun gestoppt');

        } else {
            echo sendError($error);
        }
    }

    if (isset($_POST['sendStart'])) {
        $error = null;

        if ($status->result->state == 'running') {
            $error = 'Dein Server ist bereits gestartet';
        }

        if (empty($error)) {

            $serverStatus = 'ONLINE';
            $hosterapi->start($vm_id);
            echo sendSweetSuccess('Dein Server wird nun gestartet');

        } else {
            echo sendError($error);
        }
    }

    if (isset($_POST['sendRestart'])) {
        $error = null;

        if ($status->result->state == 'stopped') {
            $error = 'Dein Server ist bereits gestoppt';
        }

        if (empty($error)) {

            $serverStatus = 'ONLINE';
            $hosterapi->restart($vm_id);
            echo sendSweetSuccess('Dein Server wurde nun neugestartet');

        } else {
            echo sendError($error);
        }
    }

    if (isset($_POST['resetRootPW'])) {
        $error = null;

        if (empty($error)) {

            $task = $hosterapi->resetRootPW($vm_id);
            $rootpassword = $task->result->password;

            $SQLDB = $db;
            $SQL = $SQLDB->prepare("UPDATE `kvm_servers` SET `password` = :password WHERE `id` = :id");
            $SQL->execute(array(":password" => $rootpassword, ":id" => $serverInfos['id']));

            echo sendSuccess('Neues Root-Passwort wurde gesetzt.');
        } else {
            echo sendError($error);
        }
    }

}

if($serverStatus == 'ONLINE'){
    $state = '<span class="badge badge-success">Online</span>';
}

if($serverStatus == 'OFFLINE'){
    $state = '<span class="badge badge-danger">Offline</span>';
}
