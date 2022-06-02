<?php

$order_success = false;

if(isset($_POST['order_conf'])){

    $error = null;

    if($helper->getSetting('rootserver_amd') == 0){
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
    $rootpassword = $helper->generateRandomString('20');
    $hostname = 'vm'.$helper->generateRandomString(5,'1234567890').'.'.env('APP_DOMAIN');

    $serverOS = $_POST['serverOS'];

    $db_price = $site->getProductPrice('AMD_ROOTSERVER')
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

    if(empty($error)){

        $serviceID = $site->getLastKVMID();

        $task = $hosterapi->create($serverOS, $cores, $memory, $disk, $addresses);
        $SQL = $db->prepare("INSERT INTO `kvm_servers_tasks`(`service_id`, `task`) VALUES (:service_id, :task)");
        $SQL->execute(array(":service_id" => $serviceID, ":task" => json_encode($task)));

        $rootpassword = null;
        $hostname = null;
        $job_id = $task->result->jobId;

        $discord_webhook->callWebhook($username.' hat soeben einen KVM Root-Server über die VenoCIX-API bestellt.');

        $date = new DateTime(null, new DateTimeZone('Europe/Berlin'));
        $date->modify('+' . $runtime . ' day');
        $expire_at = $date->format('Y-m-d H:i:s');

        $SQLDB = $db;
        $SQL = $db->prepare("INSERT INTO `kvm_servers` (`user_id`, `job_id`, `api_name`, `hostname`, `password`, `template_id`, `cores`, `memory`, `disc`, `addresses`, `traffic`, `state`, `price`, `expire_at`) VALUES (:user_id, :job_id, :api_name, :hostname, :password, :template_id, :cores, :memory, :disc, :addresses, :traffic, :state, :price, :expire_at)");
        $SQL->execute(array(":user_id" => $userid, ":job_id" => $job_id, ":api_name" => 'HOSTERAPI', ":hostname" => $hostname, ":password" => $rootpassword, ":template_id" => $serverOS, ":cores" => $cores, ":memory" => $memory, ":disc" => $disk, ":addresses" => $addresses, ":traffic" => '10240', ":state" => 'ACTIVE', ":price" => $price, ":expire_at" => $expire_at));

        $user->removeMoney($price, $userid);
        $user->addOrder($userid,'-'.$price,'KVM Rootserver (AMD) Bestellung');

        /*if($user->getDataById($userid,'mail_order')){
            include BASE_PATH.'app/notifications/mail_templates/product/order.php';
            $mail_state = sendMail($mail, $username, $mailContent, $mailSubject);
        }*/

        $_SESSION['success_msg'] = 'Bestellung war erfolgreich.';
        header('Location: '.env('URL').'index/rootserver/');

    } else {
        echo sendError($error);
    }
}