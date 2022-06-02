<?php
require BASE_PATH . 'vendor/autoload.php';
$mollie_payment = new Mollie_Payment;

class Mollie_Payment {

    function createPayment($value, $redirectUrl, $orderId, $description, $method, $webhookUrl){

        include BASE_PATH . 'software/managing/customer/payment/mollie/initialize.php';

        if($method == 'SOFORT'){
            $payment = $mollie->payments->create([
                "amount" => [
                    "currency" => "EUR",
                    "value" => $value
                ],
                "description" => $description,
                "redirectUrl" => $redirectUrl,
                "webhookUrl" => $webhookUrl,
                "metadata" => [
                    "order_id" => $orderId,
                ],
                "method" => \Mollie\Api\Types\PaymentMethod::SOFORT
            ]);
        } elseif($method == 'PAYPAL'){
            $payment = $mollie->payments->create([
                "amount" => [
                    "currency" => "EUR",
                    "value" => $value
                ],
                "description" => $description,
                "redirectUrl" => $redirectUrl,
                "webhookUrl" => $webhookUrl,
                "metadata" => [
                    "order_id" => $orderId,
                ],
                "method" => \Mollie\Api\Types\PaymentMethod::PAYPAL
            ]);
        } elseif($method == 'GIROPAY'){
            $payment = $mollie->payments->create([
                "amount" => [
                    "currency" => "EUR",
                    "value" => $value
                ],
                "description" => $description,
                "redirectUrl" => $redirectUrl,
                "webhookUrl" => $webhookUrl,
                "metadata" => [
                    "order_id" => $orderId,
                ],
                "method" => \Mollie\Api\Types\PaymentMethod::GIROPAY
            ]);
        } elseif($method == 'CREDITCARD') {
            $payment = $mollie->payments->create([
                "amount" => [
                    "currency" => "EUR",
                    "value" => $value
                ],

                "description" => $description,
                "redirectUrl" => $redirectUrl,
                "webhookUrl" => $webhookUrl,
                "metadata" => [
                    "order_id" => $orderId,
                ],
                "method" => \Mollie\Api\Types\PaymentMethod::CREDITCARD
            ]);

        } elseif($method == 'APPLEPAY') {
            $payment = $mollie->payments->create([
                "amount" => [
                    "currency" => "EUR",
                    "value" => $value
                ],

                "description" => $description,
                "redirectUrl" => $redirectUrl,
                "webhookUrl" => $webhookUrl,
                "metadata" => [
                    "order_id" => $orderId,
                ],
                "method" => \Mollie\Api\Types\PaymentMethod::APPLEPAY
            ]);
        } elseif($method == 'PAYSAFECARD') {
            $payment = $mollie->payments->create([
                "amount" => [
                    "currency" => "EUR",
                    "value" => $value
                ],

                "description" => $description,
                "redirectUrl" => $redirectUrl,
                "webhookUrl" => $webhookUrl,
                "metadata" => [
                    "order_id" => $orderId,
                ],
                "method" => \Mollie\Api\Types\PaymentMethod::PAYSAFECARD
            ]);
        } else {
            $payment = $mollie->payments->create([
                "amount" => [
                    "currency" => "EUR",
                    "value" => $value
                ],
                "description" => $description,
                "redirectUrl" => $redirectUrl,
                "webhookUrl" => $webhookUrl,
                "metadata" => [
                    "order_id" => $orderId,
                ]
            ]);
        }

        return $payment;

        //header("Location: " . $payment->getCheckoutUrl(), true, 303);

    }

    function isPayed($paymentID){
        include BASE_PATH . 'software/managing/customer/payment/mollie/initialize.php';

        $payment = $mollie->payments->get($paymentID);

        return $payment->status;
    }

}
