<?php

if(isset($_POST['order_conf'])) {

    $error = null;

    if($helper->getSetting('rootserver_intel') == 0){
        $error = 'Die Bestellung ist derzeit deaktiviert';
    }

    if(!isset($_POST['wiederruf'])){
        $error = 'Du musst Unsere Wiederrufsbestimmungen akzeptieren';
    }

    if(!isset($_POST['agb'])){
        $error = 'Du musst Unsere AGB und Datenschutzbestimmungen akzeptieren';
    }

    if(empty($_POST['duration'])){
        $error = 'duration not found';
    }
    $runtime = $_POST['duration'];
    if($validate->duration($runtime) != true){
        $error = 'Bitte gebe eine gültige Laufzeit an';
    }

    if(empty($_POST['cores'])){
        $error = 'cores not found';
    }
    if(empty($_POST['memory'])){
        $error = 'memory not found';
    }
    if(empty($_POST['disk'])){
        $error = 'disk not found';
    }
    if(empty($_POST['addresses'])){
        $error = 'addresses not found';
    }
    if(empty($_POST['duration'])){
        $error = 'runtime not found';
    }
    if(empty($_POST['serverOS'])){
        $error = 'serverOS not found';
    }
    if($site->kvmOptionExists('1', $_POST['cores']) == false){
        $error = 'cores option entry does not exists';
    }
    if($site->kvmOptionExists('2', $_POST['memory']) == false){
        $error = 'memory option entry does not exists';
    }
    if($site->kvmOptionExists('3', $_POST['disk']) == false){
        $error = 'disk option entry does not exists';
    }
    if($site->kvmOptionExists('4', $_POST['addresses']) == false){
        $error = 'addresses option entry does not exists';
    }

    $cores = $_POST['cores'];
    $memory = $_POST['memory'];
    $disk = $_POST['disk'];
    $addresses = $_POST['addresses'];
    $runtime = $_POST['duration'];

    if($site->validateKVMOS($_POST['serverOS'], 'INTEL') == false){
        $error = 'serverOS does not exists';
    }

    $root_password = $helper->generateRandomString('22');
    $hostname = 'vm' . $helper->generateRandomString('5', '01234567890') . '.' . env('VIRTUALIZOR_HOSTNAME_DOMAIN');

    $SQL = $db->prepare("SELECT * FROM `kvm_servers_os` WHERE `id` = :id AND `type` = :type");
    $SQL->execute(array(":id" => $_POST['serverOS'], ":type" => 'INTEL'));
    $response = $SQL->fetch(PDO::FETCH_ASSOC);
    $serverOS = $response['virt_id'];

    /*
     * calculate price
     */
    $db_price = $site->getProductPrice('INTEL_ROOTSERVER')
        + $site->kvmGround('1', $cores,'price')
        + $site->kvmGround('2', $memory,'price')
        + $site->kvmGround('3', $disk,'price')
        + $site->kvmGround('4', $addresses,'price');

    /* voucher system */
    if(isset($_POST['code'])){
        $codeError = null;
        $codeData = $voucher->checkCode($_POST['code']);
        if(!$codeData){
            $codeError = 'Code ist ungültig';
        }

        if(empty($codeError)){
            $codeOkay = true;
            $voucher->useCode($_POST['code']);
        }
    }

    if($codeOkay){
        $db_price = round($db_price / 100 * (100 - round($codeData['value'])),2);

        $end_price = $db_price * $validate->getIntervalFactor($runtime);

        $price = number_format($end_price,2);
    } else {
        $db_price = $db_price * $validate->getIntervalFactor($runtime);

        $price = number_format($db_price,2);
    }
    /* voucher system end */

    if($amount < $price){
        $error = 'Du hast leider nicht genügend Guthaben';
        $_SESSION['error_msg'] = 'Du hast leider nicht genügend Guthaben';
        header('Location: '.env('URL').'payment/charge/');
        die();
    }

    if($price == 0){
        $error = 'Ungültige Anfrage bitte versuche es erneut (1001)';
    }

    if(empty($error)) {

        $serviceID = $site->getLastKVMID();
        $password = $helper->generateRandomString('26');

        if (is_null($user->getDataById($userid, 'virtualizor_password'))) {
            $task = $virtualizor->createCustom('1', $serverOS, $hostname, $root_password, $email, $disk, $memory, $cores, $password, $addresses, '0', '0', '128000', '512');

            if (is_numeric($task['done'])) {
                $update = $db->prepare("UPDATE `customers` SET `virtualizor_password` = :virtualizor_password WHERE `id` = :id");
                $update->execute(array(":virtualizor_password" => $password, ":id" => $userid));
            }
        } else {
            $task = $virtualizor->createCustom('1', $serverOS, $hostname, $root_password, $email, $disk, $memory, $cores, $password, $addresses, '0', '0', '128000', '512');
        }

        $SQL = $db->prepare("INSERT INTO `kvm_servers_tasks` (`service_id`, `task`) VALUES (:service_id, :task)");
        $SQL->execute(array(":service_id" => $serviceID, ":task" => json_encode($task)));

        $t = json_encode($task);
    }

    if(empty($error)) {

        if(is_numeric($task['done'])) {
            $date = new DateTime(null, new DateTimeZone('Europe/Berlin'));
            $date->modify('+' . $runtime . ' day');
            $expire_at = $date->format('Y-m-d H:i:s');

            $SQL = $db->prepare("INSERT INTO `kvm_servers` (`user_id`, `virtualizor_id`, `slave_id`, `api_name`, `hostname`, `password`, `template_id`, `cores`, `memory`, `disc`, `addresses`, `traffic`, `state`, `price`, `expire_at`) VALUES (:user_id, :virtualizor_id, :slave_id, :api_name, :hostname, :password, :template_id, :cores, :memory, :disc, :addresses, :traffic, :state, :price, :expire_at)");
            $SQL->execute(array(":user_id" => $userid, ":virtualizor_id" => $task['done'], ":slave_id" => '1', ":api_name" => 'VIRTUALIZOR', ":hostname" => $hostname, ":password" => $root_password, ":template_id" => $serverOS, ":cores" => $cores, ":memory" => $memory, ":disc" => $disk, ":addresses" => $addresses, ":traffic" => '512', ":state" => 'ACTIVE', ":price" => $price, ":expire_at" => $expire_at));

            $user->removeMoney($price, $userid);
            $user->addOrder($userid, '-' . $price, 'KVM Rootserver (Intel) Bestellung');

            $discord->callWebhook($username . ' hat soeben einen KVM Rootserver (Intel) bestellt.');

            $_SESSION['success_msg'] = 'Bestellung war erfolgreich.';
            //$_SESSION['success_msg'] = 'Bestellung war erfolgreich.';
            header('Location: ' . env('URL') . 'index/rootserver/');
        } else {
            echo sendError('Fehler bei der Erstellung des Servers. (Code: 2000 - ' . $task['done'] . ')');
            //$_SESSION['error_msg'] = 'Fehler bei der Erstellung des Servers. (Code: 2000 - ' . $task['done'] . ')';
        }
    } else {
        echo sendError($error);
    }
}