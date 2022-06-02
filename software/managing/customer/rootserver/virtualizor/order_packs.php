<?php

if(isset($_POST['order_packs'])) {

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

    if(empty($_POST['serverOS'])) {
        $error = 'Bitte wähle ein Betriebssystem aus.';
    }

    if(empty($_POST['plan_id'])) {
        $error = 'Bitte wähle ein Paket aus.';
    }

    $runtime = 30;
    $plan_id = $_POST['plan_id'];
    $address = '1';

    $root_password = $helper->generateRandomString('22');
    $hostname = 'vm' . $helper->generateRandomString('5', '01234567890') . '.' . env('VIRTUALIZOR_HOSTNAME_DOMAIN');

    $SQL = $db->prepare("SELECT * FROM `kvm_servers_os` WHERE `id` = :id AND `type` = :type");
    $SQL->execute(array(":id" => $_POST['serverOS'], ":type" => 'INTEL'));
    $response = $SQL->fetch(PDO::FETCH_ASSOC);

    $serverOS = $response['virt_id'];

    if($site->validateKVMOS($_POST['serverOS'], 'INTEL') == false) {
        $error = 'Betriebssystem existiert nicht.';
    }

    $SQL2 = $db->prepare("SELECT * FROM `kvm_servers_packs` WHERE `id` = :id AND `type` = :type");
    $SQL2->execute(array(":id" => $plan_id, ":type" => 'INTEL'));
    $response2 = $SQL2->fetch(PDO::FETCH_ASSOC);

    /*
     * calculate price
     */
    $price = number_format($response2['price'], 2);

    if($amount < $price) {
        $error = 'Du hast leider nicht genügend Guthaben! Bitte lade Guthaben auf, um die Bestellung auszuführen.';
    }

    if($price == 0) {
        $error = 'Ungültige Anfrage! Bitte versuche es erneut. (Code: 1001)';
    }

    if(empty($error)) {

        $serviceID = $site->getLastKVMID();
        $password = $helper->generateRandomString('26');

        if (is_null($user->getDataById($userid, 'virtualizor_password'))) {
            $task = $virtualizor->create($response2['slave_id'], $serverOS, $response2['virt_id'], $hostname, $root_password, $email, $password, $response2['addresses'], '', '');

            if (is_numeric($task['done'])) {
                $update = $db->prepare("UPDATE `customers` SET `virtualizor_password` = :virtualizor_password WHERE `id` = :id");
                $update->execute(array(":virtualizor_password" => $password, ":id" => $userid));
            }
        } else {
            $task = $virtualizor->create($response2['slave_id'], $serverOS, $response2['virt_id'], $hostname, $root_password, $email, $password, $response2['addresses'], '', '');
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

            $SQL = $db->prepare("INSERT INTO `kvm_servers` (`user_id`, `virtualizor_id`, `slave_id`, `api_name`, `hostname`, `password`, `template_id`, `cores`, `memory`, `disc`, `addresses`, `traffic`, `pack_name`, `state`, `price`, `expire_at`) VALUES (:user_id, :virtualizor_id, :slave_id, :api_name, :hostname, :password, :template_id, :cores, :memory, :disc, :addresses, :traffic, :pack_name, :state, :price, :expire_at)");
            $SQL->execute(array(":user_id" => $userid, ":virtualizor_id" => $task['done'], ":slave_id" => $response2['slave_id'], ":api_name" => 'VIRTUALIZOR', ":hostname" => $hostname, ":password" => $root_password, ":template_id" => $serverOS, ":cores" => $response2['cores'], ":memory" => $response2['memory'], ":disc" => $response2['disk'], ":addresses" => $response2['addresses'], ":traffic" => $response2['traffic'], ":pack_name" => $response2['id'], ":state" => 'ACTIVE', ":price" => $price, ":expire_at" => $expire_at));

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
        //$_SESSION['error_msg'] = $error;
        echo sendError($error);
    }
}