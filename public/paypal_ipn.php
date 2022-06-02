<?php
$currPage = 'system_paypal ipn';

include_once '../system.php';
include_once BASE_PATH.'vendor/autoload.php';
include_once BASE_PATH.'software/Kernel.php';
include_once BASE_PATH.'software/backend/autoload.php';
include_once BASE_PATH.'software/notify/sendMail.php';

include BASE_PATH.'software/managing/customer/payment/paypal/functions.php';

$paypalUrl = $enableSandbox ? 'https://www.sandbox.paypal.com/cgi-bin/webscr' : 'https://www.paypal.com/cgi-bin/webscr';

if (isset($_POST["txn_id"]) && isset($_POST["txn_type"])) {

    $SQL = $db->prepare("SELECT * FROM `customers_charge_transactions` WHERE `state` = 'success' AND `tid` = :tid");
    $SQL->execute(array(":tid" => $_POST['txn_id']));
    if($SQL->rowCount() == 0) {

        $data = [
            'item_name' => $_POST['item_name'],
            'item_number' => $_POST['item_number'],
            'payment_status' => $_POST['payment_status'],
            'payment_amount' => $_POST['mc_gross'],
            'payment_currency' => $_POST['mc_currency'],
            'txn_id' => $_POST['txn_id'],
            'receiver_email' => $_POST['receiver_email'],
            'payer_email' => $_POST['payer_email'],
            'custom' => $_POST['custom'],
        ];

        if (verifyTransaction($_POST)){
            $custom = $data['custom'];
            $user_id = $user->getDataBySession($custom,'id');
            $money = $data['payment_amount'];
            $username = $user->getDataById($user_id, 'username');

            $site->addTransaction($user_id,'PayPal','success', $money,'Guthabenaufladung mit Paypal', $data['txn_id']);
            $user->addMoney($money, $user_id);

            $discord_webhook->callWebhook('Soeben wurden '.number_format($money, 2).'€ aufgeladen von '.$username.' (PayPal)');

            if(!is_null($helper->getSetting('payment_bonus'))) {
                $bonus = number_format($money, 2) / 100 * $helper->getSetting('payment_bonus');

                $tid = $site->checkTransaction($user_id, $_POST["txn_id"], 'id');
                $site->addTransaction($user_id, 'System', 'success', number_format($bonus, 2), 'Aufladebonus für die Aufladung mit der ID: #' . $tid, $_POST["txn_id"]);
                #$user->addMoney($bonus, $user_id);
                $user->addMoney($bonus, $user_id);
                $discord_webhook->callWebhook('Zu der Transaktion von ' . $username . ' wurden ' . $bonus . '€ hinzugefügt. (Bonus von ' . $helper->getSetting('payment_bonus'). '%)');
            }
        }

    } else {
        echo 'Transaction already confirmed';
    }

} else {
    die('error');
}