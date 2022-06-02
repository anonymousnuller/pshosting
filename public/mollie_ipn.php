<?php
$currPage = 'system_mollie ipn';

include_once '../system.php';
include_once BASE_PATH . 'vendor/autoload.php';
include_once BASE_PATH . 'software/Kernel.php';
include_once BASE_PATH . 'software/backend/autoload.php';
include_once BASE_PATH . 'software/notify/sendMail.php';
include BASE_PATH . 'software/managing/customer/payment/mollie/initialize.php';
include BASE_PATH . 'software/managing/customer/payment/mollie/api_manager.php';

if(isset($_POST['id'])){

    $transactionId = $_POST['id'];
    //$status = $_POST['status'];

    $SQL = $db->prepare("SELECT * FROM `customers_charge_transactions` WHERE `tid` = :tid");
    $SQL->execute(array(":tid" => $transactionId));
    if($SQL->rowCount() == 1) {


        $status = $mollie_payment->isPayed($transactionId);

        if ($status == 'paid' || $status == 'authorized') {

            $state = 'success';

            $updatePayment = $db->prepare("UPDATE `customers_charge_transactions` SET `state` = :state WHERE `tid` = :tid");
            $updatePayment->execute(array(":state" => $state, ":tid" => $transactionId));

            $SQLGetInfos = $db->prepare("SELECT * FROM `customers_charge_transactions` WHERE `tid` = :tid");
            $SQLGetInfos->execute(array(":tid" => $transactionId));
            $infos = $SQLGetInfos->fetch(PDO::FETCH_ASSOC);

            $SQLGetInfos = $db->prepare("SELECT * FROM `customers` WHERE `id` = :user_id");
            $SQLGetInfos->execute(array(":user_id" => $infos['user_id']));
            $userInfo = $SQLGetInfos->fetch(PDO::FETCH_ASSOC);

            #$discord_webhook->callWebhook('Soeben wurden '.$infos['amount'].'€ aufgeladen von '.$userInfo['username'].' (Mollie-Modul)');

            $user->addMoney($infos['amount'], $infos['user_id']);

            if(!is_null($helper->getSetting('payment_bonus'))) {
                $bonus = $infos['amount'] / 100 * $helper->getSetting('payment_bonus');

                $tid = $site->checkTransaction($infos['user_id'], $transactionId, 'id');
                $site->addTransaction($infos['user_id'], 'System', 'success', number_format($bonus, 2), 'Aufladebonus für die Aufladung mit der ID: #' . $tid, $transactionId);
                #$user->addMoney($bonus, $user_id);
                $user->addMoney(number_format($bonus, 2), $infos['user_id']);
                #$discord_webhook->callWebhook('Zu der Transaktion von ' . $user->getDataById($infos['user_id'], 'username') . ' wurden ' . $bonus . '€ hinzugefügt. (Bonus von ' . $helper->getSetting('payment_bonus'). '%)');
            }

            $body = file_get_contents(BASE_PATH . 'public/email_templates/customer/payment/charge_done.html');
            $body = str_replace('%username%', $user->getDataById($infos['user_id'], 'username'), $body);
            $body = str_replace('%gateway%', 'Mollie', $body);
            $body = str_replace('%price%', number_format($infos['amount'], 2) . '€', $body);
            $body = str_replace('%state%', 'Erfolgreich', $body);
            $body = str_replace('%transaction_id%', $infos['tid'], $body);
            $body = str_replace('%date%', $helper->formatDateWithoutTime($infos['updated_at']), $body);
            $body = str_replace('%time%', $helper->formatDateTimeOnly($infos['updated_at']), $body);
            $body = str_replace('%project_name%', env('EMAIL_PROJECT_NAME'), $body);
            sendMail($user->getDataById($infos['user_id'], 'email'), $user->getDataById($infos['user_id'], 'username'), $body, 'Deine Aufladung war erfolgreich');

        } elseif ($status == 'expired') {

            $state = 'expired';

            $updatePayment = $db->prepare("UPDATE `customers_charge_transactions` SET `state` = :state WHERE `tid` = :tid");
            $updatePayment->execute(array(":state" => $state, ":tid" => $transactionId));

            $SQLGetInfos = $db->prepare("SELECT * FROM `customers_charge_transactions` WHERE `tid` = :tid");
            $SQLGetInfos->execute(array(":tid" => $transactionId));
            $infos = $SQLGetInfos->fetch(PDO::FETCH_ASSOC);

            $SQLGetInfos = $db->prepare("SELECT * FROM `customers` WHERE `id` = :user_id");
            $SQLGetInfos->execute(array(":user_id" => $infos['user_id']));
            $userInfo = $SQLGetInfos->fetch(PDO::FETCH_ASSOC);

            $body = file_get_contents(BASE_PATH . 'public/email_templates/customer/payment/charge_expired.html');
            $body = str_replace('%username%', $user->getDataById($infos['user_id'], 'username'), $body);
            $body = str_replace('%gateway%', 'Mollie', $body);
            $body = str_replace('%price%', number_format($infos['amount'], 2) . '€', $body);
            $body = str_replace('%state%', 'Abgelaufen', $body);
            $body = str_replace('%transaction_id%', $infos['tid'], $body);
            $body = str_replace('%project_name%', env('EMAIL_PROJECT_NAME'), $body);
            sendMail($user->getDataById($infos['user_id'], 'email'), $user->getDataById($infos['user_id'], 'username'), $body, 'Deine Aufladung ist abgelaufen');


        } elseif ($status == 'failed') {

            $state = 'failed';

            $updatePayment = $db->prepare("UPDATE `customers_charge_transactions` SET `state` = :state WHERE `tid` = :tid");
            $updatePayment->execute(array(":state" => $state, ":tid" => $transactionId));

            $SQLGetInfos = $db->prepare("SELECT * FROM `customers_charge_transactions` WHERE `tid` = :tid");
            $SQLGetInfos->execute(array(":tid" => $transactionId));
            $infos = $SQLGetInfos->fetch(PDO::FETCH_ASSOC);

            $SQLGetInfos = $db->prepare("SELECT * FROM `customers` WHERE `id` = :user_id");
            $SQLGetInfos->execute(array(":user_id" => $infos['user_id']));
            $userInfo = $SQLGetInfos->fetch(PDO::FETCH_ASSOC);

            $body = file_get_contents(BASE_PATH . 'public/email_templates/customer/payment/charge_failed.html');
            $body = str_replace('%username%', $user->getDataById($infos['user_id'], 'username'), $body);
            $body = str_replace('%gateway%', 'Mollie', $body);
            $body = str_replace('%price%', number_format($infos['amount'], 2) . '€', $body);
            $body = str_replace('%state%', 'Fehlgeschlagen', $body);
            $body = str_replace('%transaction_id%', $infos['tid'], $body);
            $body = str_replace('%project_name%', env('EMAIL_PROJECT_NAME'), $body);
            sendMail($user->getDataById($infos['user_id'], 'email'), $user->getDataById($infos['user_id'], 'username'), $body, 'Deine Aufladung ist fehlgeschlagen');

        } elseif ($status == 'canceled') {

            $state = 'canceled';

            $updatePayment = $db->prepare("UPDATE `customers_charge_transactions` SET `state` = :state WHERE `tid` = :tid");
            $updatePayment->execute(array(":state" => $state, ":tid" => $transactionId));

            $SQLGetInfos = $db->prepare("SELECT * FROM `customers_charge_transactions` WHERE `tid` = :tid");
            $SQLGetInfos->execute(array(":tid" => $transactionId));
            $infos = $SQLGetInfos->fetch(PDO::FETCH_ASSOC);

            $SQLGetInfos = $db->prepare("SELECT * FROM `customers` WHERE `id` = :user_id");
            $SQLGetInfos->execute(array(":user_id" => $infos['user_id']));
            $userInfo = $SQLGetInfos->fetch(PDO::FETCH_ASSOC);

            $body = file_get_contents(BASE_PATH . 'public/email_templates/customer/payment/charge_canceled.html');
            $body = str_replace('%username%', $user->getDataById($infos['user_id'], 'username'), $body);
            $body = str_replace('%gateway%', 'Mollie', $body);
            $body = str_replace('%price%', number_format($infos['amount'], 2) . '€', $body);
            $body = str_replace('%state%', 'Abgebrochen', $body);
            $body = str_replace('%transaction_id%', $infos['tid'], $body);
            $body = str_replace('%project_name%', env('EMAIL_PROJECT_NAME'), $body);
            sendMail($user->getDataById($infos['user_id'], 'email'), $user->getDataById($infos['user_id'], 'username'), $body, 'Deine Aufladung ist abgebrochen worden');

        } elseif($status == 'refunded') {
            $state = 'refunded';

            $updatePayment = $db->prepare("UPDATE `customers_charge_transactions` SET `state` = :state WHERE `tid` = :tid");
            $updatePayment->execute(array(":state" => $state, ":tid" => $transactionId));
        }

    }

} else {
    echo 'Something went wrong...';
}