<?php
$currPage = 'system_runtime queue';
include BASE_PATH . 'software/controller/PageController.php';

//Time now
$date = new DateTime(null, new DateTimeZone('Europe/Berlin'));
$dateTimeNow = $date->format('Y-m-d H:i:s');

//Time minus 3 days
$dateMinus = new DateTime(null, new DateTimeZone('Europe/Berlin'));
$dateMinus->modify('-3 day');
$dateTimeMinus3Days = $dateMinus->format('Y-m-d H:i:s');

//Time plus 3 days
$datePlus = new DateTime(null, new DateTimeZone('Europe/Berlin'));
$datePlus->modify('+3 day');
$dateTimePlus3Days = $datePlus->format('Y-m-d H:i:s');

// Time plesk
$dateTimePlesk = new DateTime(null, new DateTimeZone('Europe/Berlin'));
$dateTimePleskNow = $dateTimePlesk->format('Y-m-d');

$key = $helper->protect($_GET['key']);
if($key == env('CRONE_KEY')){

    /*0
     * kvm servers runtime
     */

    // send mail to runtime message
    $kvmEmail = $db->prepare("SELECT * FROM `kvm_servers` WHERE `deleted_at` IS NULL");
    $kvmEmail->execute();
    if($kvmEmail->rowCount() != 0) {
        while($row = $kvmEmail->fetch(PDO::FETCH_ASSOC)) {


            $product_name = 'KVM-Rootserver #' . $row['id'];

            // check mail settings of customer
            $diffInDays = $site->getDiffInDays($row['expire_at']);

            if($diffInDays != $row['days']) {
                if($diffInDays == 1) {
                    $mailSubject = 'Dein ' . $product_name . ' läuft in ' . $diffInDays . ' Tag aus';
                } else {
                    $mailSubject = 'Dein ' . $product_name . ' läuft in ' . $diffInDays . ' Tagen aus';
                }
                // check 14 days
                if($diffInDays == 14) {
                    $body = file_get_contents(BASE_PATH . 'public/email_templates/customer/products/runout.html');
                    $body = str_replace('%username%', $user->getDataById($row['user_id'], 'username'), $body);
                    $body = str_replace('%product_name%', $product_name, $body);
                    $body = str_replace('%days%', '14 Tagen', $body);
                    $body = str_replace('%runout_date%', $helper->formatDateNormal($row['expire_at']), $body);
                    $body = str_replace('%price%', number_format($row['price'], 2) . '€', $body);
                    $body = str_replace('%project_name%', env('EMAIL_PROJECT_NAME'), $body);
                    sendMail($user->getDataById($row['user_id'], 'email'), $user->getDataById($row['user_id'], 'username'), $body, $mailSubject);
                }

                if($diffInDays == 7) {

                    $body = file_get_contents(BASE_PATH . 'public/email_templates/customer/products/runout.html');
                    $body = str_replace('%username%', $user->getDataById($row['user_id'], 'username'), $body);
                    $body = str_replace('%product_name%',  $product_name, $body);
                    $body = str_replace('%days%', '7 Tagen', $body);
                    $body = str_replace('%runout_date%', $helper->formatDateNormal($row['expire_at']), $body);
                    $body = str_replace('%price%', number_format($row['price'], 2) . '€', $body);
                    $body = str_replace('%project_name%', env('EMAIL_PROJECT_NAME'), $body);
                    sendMail($user->getDataById($row['user_id'], 'email'), $user->getDataById($row['user_id'], 'username'), $body, $mailSubject);
                }

                if($diffInDays == 3) {

                    $body = file_get_contents(BASE_PATH . 'public/email_templates/customer/products/runout.html');
                    $body = str_replace('%username%', $user->getDataById($row['user_id'], 'username'), $body);
                    $body = str_replace('%product_name%',  $product_name, $body);
                    $body = str_replace('%days%', '3 Tagen', $body);
                    $body = str_replace('%runout_date%', $helper->formatDateNormal($row['expire_at']), $body);
                    $body = str_replace('%price%', number_format($row['price'], 2) . '€', $body);
                    $body = str_replace('%project_name%', env('EMAIL_PROJECT_NAME'), $body);
                    sendMail($user->getDataById($row['user_id'], 'email'), $user->getDataById($row['user_id'], 'username'), $body, $mailSubject);
                }

                if($diffInDays == 1) {
                    $body = file_get_contents(BASE_PATH . 'public/email_templates/customer/products/runout.html');
                    $body = str_replace('%username%', $user->getDataById($row['user_id'], 'username'), $body);
                    $body = str_replace('%product_name%',  $product_name, $body);
                    $body = str_replace('%days%', '1 Tag', $body);
                    $body = str_replace('%price%', number_format($row['price'], 2) . '€', $body);
                    $body = str_replace('%runout_date%', $helper->formatDateNormal($row['expire_at']), $body);
                    $body = str_replace('%project_name%', env('EMAIL_PROJECT_NAME'), $body);
                    sendMail($user->getDataById($row['user_id'], 'email'), $user->getDataById($row['user_id'], 'username'), $body, $mailSubject);
                }

                /*if($diffInDays == 0.5) {
                    $product_name = 'KVM Root-Server (' . $type . ')';
                    // -> insert mail send
                }*/
            }

            // update days in to the database
            $SQL2 = $db->prepare("UPDATE `kvm_servers` SET `days` = :days WHERE `id` = :id");
            $SQL2->execute(array(":days" => $diffInDays, ":id" => $row['id']));
        }
    }


    // suspend kvm servers by type
    $kvm_intel = $db->prepare("SELECT * FROM `kvm_servers` WHERE `expire_at` < :dateTimeNow AND `state` = 'ACTIVE'");
    $kvm_intel->execute(array(":dateTimeNow" => $dateTimeNow));
    if($kvm_intel->rowCount() != 0) {
        while($row = $kvm_intel->fetch(PDO::FETCH_ASSOC)) {
            $SQL = $db->prepare("UPDATE `kvm_servers` SET `state` = 'SUSPENDED' WHERE `id` = :id");
            $SQL->execute(array(":id" => $row['id']));

            try {
                $vm_id = $row['id'];

                $kvm->stopServer($row['node_id'], $vm_id);
                $discord_webhook->callWebhook('KVM mit der ID ' . $row['id'] . ' wurde automatisch gesperrt.');

                $mailSubject = 'Dein KVM-Rootserver #' . $row['id'] . ' wurde gesperrt';

                $body = file_get_contents(BASE_PATH . 'public/email_templates/customer/products/suspended.html');
                $body = str_replace('%username%', $user->getDataById($row['user_id'], 'username'), $body);
                $body = str_replace('%product_name%',  $product_name, $body);
                $body = str_replace('%days%', '3 Tagen', $body);
                $body = str_replace('%price%', number_format($row['price'], 2) . '€', $body);
                $body = str_replace('%delete_date%', $helper->formatDateNormal($dateTimeMinus3Days), $body);
                $body = str_replace('%project_name%', env('EMAIL_PROJECT_NAME'), $body);
                sendMail($user->getDataById($row['user_id'], 'email'), $user->getDataById($row['user_id'], 'username'), $body, $mailSubject);
            } catch(Exception $e) {
                // nothing to do
            }


            echo 'Suspend ' . $row['id'] . ' kvm server';
        }
    }

    // delete kvm server by type intel
    $kvm_intel_suspend = $db->prepare("SELECT * FROM `kvm_servers` WHERE `expire_at` < :dateTimeMinusDays AND `state` = 'SUSPENDED'");
    $kvm_intel_suspend->execute(array(":dateTimeMinusDays" => $dateTimeMinus3Days));
    if($kvm_intel_suspend->rowCount() != 0) {
        while($row = $kvm_intel_suspend->fetch(PDO::FETCH_ASSOC)) {
            $SQL = $db->prepare("UPDATE `kvm_servers` SET `state` = 'DELETED', `deleted_at` = :deleted_at WHERE `id` = :id");
            $SQL->execute(array(":deleted_at" => $dateTimeNow, ":id" => $row['id']));

            try {
                $getCredentials = $db->prepare("SELECT * FROM `kvm_servers_nodes` WHERE `id` = :id ORDER BY `id` DESC LIMIT 1;");
                $getCredentials->execute(array(":id" => $row['node_id']));
                $credentials = $getCredentials->fetch(PDO::FETCH_ASSOC);

                $task = $kvm->exec('qm destroy ' . $row['id'], $credentials);
                $SQL4 = $db->prepare("INSERT INTO `kvm_servers_tasks`(`service_id`, `task`) VALUES (:service_id, :task)");
                $SQL4->execute(array(":service_id" => $row['id'], ":task" => $task));

                $SQL2 = $db->prepare("UPDATE `ipv4_pool` SET `service_id` = NULL, `service_type` = NULL WHERE `service_id` = :id");
                $SQL2->execute(array(":id" => $row['id']));

                $product_name = 'KVM-Rootserver #' . $row['id'];

                $mailSubject = 'Dein KVM-Rootserver #' . $row['id'] . ' wurde gelöscht';
                $body = file_get_contents(BASE_PATH . 'public/email_templates/customer/products/deleted.html');
                $body = str_replace('%username%', $user->getDataById($row['user_id'], 'username'), $body);
                $body = str_replace('%product_name%',  $product_name, $body);
                $body = str_replace('%delete_date%', $helper->formatDateNormal($dateTimeMinus3Days), $body);
                $body = str_replace('%project_name%', env('EMAIL_PROJECT_NAME'), $body);
                sendMail($user->getDataById($row['user_id'], 'email'), $user->getDataById($row['user_id'], 'username'), $body, $mailSubject);

                $discord_webhook->callWebhook('KVM mit der ID ' . $row['id'] . ' wurde gelöscht.');
            } catch(Exception $e) {
                // nothing to do
            }

            echo 'deleted kvm server with id ' . $row['id'];
        }
    }


    /* ======================================================================================================================================== */

    /* ======================================================================================================================================== */
    $webspaceEmail = $db->prepare("SELECT * FROM `webspaces` WHERE `deleted_at` IS NULL");
    $webspaceEmail->execute();
    if ($webspaceEmail->rowCount() != 0) {
        while ($row = $webspaceEmail->fetch(PDO::FETCH_ASSOC)) {

            if($user->getDataById($row['user_id'],'mail_runtime')){
                $diffInDays = $site->getDiffInDays($row['expire_at']);
                if($diffInDays != $row['days']){

                    if($diffInDays == 14) {
                        $product_name = 'Webspace #' . $row['id'];

                        $body = file_get_contents(BASE_PATH . 'public/email_templates/customer/products/runout.html');
                        $body = str_replace('%username%', $user->getDataById($row['user_id'], 'username'), $body);
                        $body = str_replace('%product_name%',  $product_name, $body);
                        $body = str_replace('%days%', '14 Tagen', $body);
                        $body = str_replace('%runout_date%', $helper->formatDateNormal($row['expire_at']), $body);
                        $body = str_replace('%price%', number_format($row['price'], 2) . '€', $body);
                        $body = str_replace('%project_name%', env('EMAIL_PROJECT_NAME'), $body);
                        sendMail($user->getDataById($row['user_id'], 'email'), $user->getDataById($row['user_id'], 'username'), $body, 'Dein ' . $product_name . ' läuft in 14 Tagen aus');
                    }

                    if($diffInDays == 7) {
                        $product_name = 'Webspace #' . $row['id'];

                        $body = file_get_contents(BASE_PATH . 'public/email_templates/customer/products/runout.html');
                        $body = str_replace('%username%', $user->getDataById($row['user_id'], 'username'), $body);
                        $body = str_replace('%product_name%',  $product_name, $body);
                        $body = str_replace('%days%', '7 Tagen', $body);
                        $body = str_replace('%runout_date%', $helper->formatDateNormal($row['expire_at']), $body);
                        $body = str_replace('%price%', number_format($row['price'], 2) . '€', $body);
                        $body = str_replace('%project_name%', env('EMAIL_PROJECT_NAME'), $body);
                        sendMail($user->getDataById($row['user_id'], 'email'), $user->getDataById($row['user_id'], 'username'), $body, 'Dein ' . $product_name . ' läuft in 7 Tagen aus');
                    }

                    if($diffInDays == 3) {
                        $product_name = 'Webspace #' . $row['id'];

                        $body = file_get_contents(BASE_PATH . 'public/email_templates/customer/products/runout.html');
                        $body = str_replace('%username%', $user->getDataById($row['user_id'], 'username'), $body);
                        $body = str_replace('%product_name%',  $product_name, $body);
                        $body = str_replace('%days%', '3 Tagen', $body);
                        $body = str_replace('%runout_date%', $helper->formatDateNormal($row['expire_at']), $body);
                        $body = str_replace('%price%', number_format($row['price'], 2) . '€', $body);
                        $body = str_replace('%project_name%', env('EMAIL_PROJECT_NAME'), $body);
                        sendMail($user->getDataById($row['user_id'], 'email'), $user->getDataById($row['user_id'], 'username'), $body, 'Dein ' . $product_name . ' läuft in 3 Tagen aus');
                    }

                    if($diffInDays == 1) {
                        $product_name = 'Webspace #' . $row['id'];

                        $body = file_get_contents(BASE_PATH . 'public/email_templates/customer/products/runout.html');
                        $body = str_replace('%username%', $user->getDataById($row['user_id'], 'username'), $body);
                        $body = str_replace('%product_name%',  $product_name, $body);
                        $body = str_replace('%days%', '1 Tag', $body);
                        $body = str_replace('%price%', number_format($row['price'], 2) . '€', $body);
                        $body = str_replace('%runout_date%', $helper->formatDateNormal($row['expire_at']), $body);
                        $body = str_replace('%project_name%', env('EMAIL_PROJECT_NAME'), $body);
                        sendMail($user->getDataById($row['user_id'], 'email'), $user->getDataById($row['user_id'], 'username'), $body, 'Dein ' . $product_name . ' läuft in 1 Tagen aus');
                    }

                    $SQL = $db->prepare("UPDATE `webspaces` SET `days` = :days WHERE `id` = :id");
                    $SQL->execute(array(":days" => $diffInDays, ":id" => $row['id']));
                }
            }

        }
    }

    $webspaceDB = $db->prepare("SELECT * FROM `webspaces` WHERE `expire_at` < :dateTimeNow AND `state` = 'active'");
    $webspaceDB->execute(array(":dateTimeNow" => $dateTimeNow));
    if ($webspaceDB->rowCount() != 0) {
        while ($row = $webspaceDB->fetch(PDO::FETCH_ASSOC)) {

            $SQL = $db->prepare("UPDATE `webspaces` SET `state`='suspended' WHERE `id` = :id");
            $SQL->execute(array(":id" => $row['id']));

            $product_name = 'Webspace #' . $row['id'];

            $body = file_get_contents(BASE_PATH . 'public/email_templates/customer/products/suspended.html');
            $body = str_replace('%username%', $user->getDataById($row['user_id'], 'username'), $body);
            $body = str_replace('%product_name%',  $product_name, $body);
            $body = str_replace('%days%', '3 Tagen', $body);
            $body = str_replace('%price%', number_format($row['price'], 2) . '€', $body);
            $body = str_replace('%delete_date%', $helper->formatDateNormal($dateTimeMinus3Days), $body);
            $body = str_replace('%project_name%', env('EMAIL_PROJECT_NAME'), $body);
            sendMail($user->getDataById($row['user_id'], 'email'), $user->getDataById($row['user_id'], 'username'), $body, 'Dein ' . $product_name . ' wurde gesperrt');

            echo 'Suspended Webspace #'.$row['id'];

        }
    }

    $webspaceSuspendedDB = $db->prepare("SELECT * FROM `webspaces` WHERE `expire_at` < :dateTimeMinusDays AND `state` = 'suspended'");
    $webspaceSuspendedDB->execute(array(":dateTimeMinusDays" => $dateTimeMinus3Days));
    if ($webspaceSuspendedDB->rowCount() != 0) {
        while ($row = $webspaceSuspendedDB->fetch(PDO::FETCH_ASSOC)) {

            $SQL = $db->prepare("UPDATE `webspaces` SET `state`='deleted', `deleted_at` = :deleted_at WHERE `id` = :id");
            $SQL->execute(array(":deleted_at" => $dateTimeNow, ":id" => $row['id']));

            try {
                $plesk->delete($row['webspace_id']);

                $product_name = 'Webspace #' . $row['id'];

                $mailSubject = 'Dein Webspace #' . $row['id'] . ' wurde gelöscht';
                $body = file_get_contents(BASE_PATH . 'public/email_templates/customer/products/deleted.html');
                $body = str_replace('%username%', $user->getDataById($row['user_id'], 'username'), $body);
                $body = str_replace('%product_name%',  $product_name, $body);
                $body = str_replace('%delete_date%', $helper->formatDateNormal($dateTimeMinus3Days), $body);
                $body = str_replace('%project_name%', env('EMAIL_PROJECT_NAME'), $body);
                sendMail($user->getDataById($row['user_id'], 'email'), $user->getDataById($row['user_id'], 'username'), $body, $mailSubject);
            } catch (Exception $e){

            }

            echo 'Deleted Webspace #'.$row['id'];

        }
    }
    /* ======================================================================================================================================== */

    /* ======================================================================================================================================== */

    $serviceDb = $db->prepare("SELECT * FROM `services` WHERE `deleted_at` IS NULL");
    $serviceDb->execute();
    if($serviceDb->rowCount() != 0) {
        while($row = $serviceDb->fetch(PDO::FETCH_ASSOC)) {
            $product_name = 'Service (' . $row['name'] . ') #' . $row['id'];

            $diffInDays = $site->getDiffInDays($row['expire_at']);
            if($diffInDays != $row['days']){

                if($diffInDays == 14) {

                    $body = file_get_contents(BASE_PATH . 'public/email_templates/customer/products/runout.html');
                    $body = str_replace('%username%', $user->getDataById($row['user_id'], 'username'), $body);
                    $body = str_replace('%product_name%',  $product_name, $body);
                    $body = str_replace('%days%', '14 Tagen', $body);
                    $body = str_replace('%runout_date%', $helper->formatDateNormal($row['expire_at']), $body);
                    $body = str_replace('%price%', number_format($row['price'], 2) . '€', $body);
                    $body = str_replace('%project_name%', env('EMAIL_PROJECT_NAME'), $body);
                    sendMail($user->getDataById($row['user_id'], 'email'), $user->getDataById($row['user_id'], 'username'), $body, 'Dein ' . $product_name . ' läuft in 14 Tagen aus');
                }

                if($diffInDays == 7) {

                    $body = file_get_contents(BASE_PATH . 'public/email_templates/customer/products/runout.html');
                    $body = str_replace('%username%', $user->getDataById($row['user_id'], 'username'), $body);
                    $body = str_replace('%product_name%',  $product_name, $body);
                    $body = str_replace('%days%', '7 Tagen', $body);
                    $body = str_replace('%runout_date%', $helper->formatDateNormal($row['expire_at']), $body);
                    $body = str_replace('%price%', number_format($row['price'], 2) . '€', $body);
                    $body = str_replace('%project_name%', env('EMAIL_PROJECT_NAME'), $body);
                    sendMail($user->getDataById($row['user_id'], 'email'), $user->getDataById($row['user_id'], 'username'), $body, 'Dein ' . $product_name . ' läuft in 7 Tagen aus');
                }

                if($diffInDays == 3) {

                    $body = file_get_contents(BASE_PATH . 'public/email_templates/customer/products/runout.html');
                    $body = str_replace('%username%', $user->getDataById($row['user_id'], 'username'), $body);
                    $body = str_replace('%product_name%',  $product_name, $body);
                    $body = str_replace('%days%', '3 Tagen', $body);
                    $body = str_replace('%runout_date%', $helper->formatDateNormal($row['expire_at']), $body);
                    $body = str_replace('%price%', number_format($row['price'], 2) . '€', $body);
                    $body = str_replace('%project_name%', env('EMAIL_PROJECT_NAME'), $body);
                    sendMail($user->getDataById($row['user_id'], 'email'), $user->getDataById($row['user_id'], 'username'), $body, 'Dein ' . $product_name . ' läuft in 3 Tagen aus');
                }

                if($diffInDays == 1) {

                    $body = file_get_contents(BASE_PATH . 'public/email_templates/customer/products/runout.html');
                    $body = str_replace('%username%', $user->getDataById($row['user_id'], 'username'), $body);
                    $body = str_replace('%product_name%',  $product_name, $body);
                    $body = str_replace('%days%', '1 Tag', $body);
                    $body = str_replace('%price%', number_format($row['price'], 2) . '€', $body);
                    $body = str_replace('%runout_date%', $helper->formatDateNormal($row['expire_at']), $body);
                    $body = str_replace('%project_name%', env('EMAIL_PROJECT_NAME'), $body);
                    sendMail($user->getDataById($row['user_id'], 'email'), $user->getDataById($row['user_id'], 'username'), $body, 'Dein ' . $product_name . ' läuft in 1 Tag aus');
                }

                $SQL = $db->prepare("UPDATE `services` SET `days` = :days WHERE `id` = :id");
                $SQL->execute(array(":days" => $diffInDays, ":id" => $row['id']));
            }
        }
    }

    $suspendService = $db->prepare("SELECT * FROM `services`  WHERE `expire_at` < :dateTimeNow AND `state` = 'ACTIVE'");
    $suspendService->execute(array(":dateTimeNow" => $dateTimeNow));
    if($suspendService->rowCount() != 0) {
        while($row = $suspendService->fetch(PDO::FETCH_ASSOC)) {

        }
    }

    /*
     * service and dedicated required
     */

    /* ======================================================================================================================================== */

    die('nothing todo');

}