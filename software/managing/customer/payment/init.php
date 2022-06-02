<?php

if(isset($_POST['startPayment'])) {

    // set error to null
    $error = null;

    if(empty($_POST['amount'])) {
        $error = 'Bitte gib einen Betrag ein.';
    }

    if(!is_numeric($_POST['amount'])) {
        $error = 'Bitte gib einen Betrag in Zahlen ein.';
    }

    // name the post of payment method
    $payment_method = $_POST['payment_method'];

    if(empty($payment_method) || $payment_method == 'select') {
        $error = 'Bitte wähle eine Zahlungsmethode aus.';
    }

    if($_POST['amount'] < 1) {
        $error = 'Bitte gib einen Betrag über 1.00€ ein.';
    }

    if($_POST['amount'] > 500) {
        $error = 'Bitte gib einen Betrag unter 500.00€ ein.';
    }

    if(empty($error)) {

        if($payment_method == 'paypal') {
            require BASE_PATH . 'software/managing/customer/payment/paypal/functions.php';

            $paypalUrl = $enableSandbox ? 'https://www.sandbox.paypal.com/cgi-bin/webscr' : 'https://www.paypal.com/cgi-bin/webscr';

            $itemName = 'Guthaben-Aufladung | kd' . $userid;
            $itemAmount = $_POST['amount'];

            $data = [];

            $data['business'] = $paypalConfig['email'];

            $data['return'] = stripslashes($paypalConfig['return_url']);
            $data['cancel_return'] = stripslashes($paypalConfig['cancel_url']);
            $data['notify_url'] = stripslashes($paypalConfig['notify_url']);

            $data['item_name'] = $itemName;
            $data['amount'] = $itemAmount;
            $data['currency_code'] = 'EUR';
            $data['custom'] = $_COOKIE['session_token'];

            $queryString = http_build_query($data);
            header('location:' . $paypalUrl . '?cmd=_xclick&' . $queryString);
            die();
        } else {
            require BASE_PATH . 'software/managing/customer/payment/mollie/api_manager.php';

            if($_POST['payment_method'] == 'CREDITCARD') {
                $name = 'Kreditkarte';
            } elseif($_POST['payment_method'] == 'APPLEPAY') {
                $name = 'Apple-Pay';
            } elseif($_POST['payment_method'] == 'GIROPAY') {
                $name = 'Giro-Pay';
            } elseif($_POST['payment_method'] == 'SOFORT') {
                $name = 'Sofort-Überweisung (Klarna)';
            } else {
                $name = 'Mollie';
            }

            $price = number_format($_POST['amount'], 2);
            $customer_ip = $_SERVER['REMOTE_ADDR'];
            $user_id = $userid;
            $orderId = time();
            $payment = $mollie_payment->createPayment($price, $helper->url() . 'payment/charge/', $orderId,'Guthabenaufladung KD-'.$user_id . ' (' . env('APP_NAME') . ')', $_POST['payment_method'], env('URL') . 'mollie_ipn.php');
            $invoice_id = json_encode($payment->id);
            $invoice_id = str_replace('"', '', $invoice_id);

            $site->addTransaction($user_id,'Mollie','pending', $price,'Guthabenaufladung mit '.$name, $invoice_id);

            header("Location: " . $payment->getCheckoutUrl(), true, 303);
        }
    } else {
        echo sendError($error);
    }
}