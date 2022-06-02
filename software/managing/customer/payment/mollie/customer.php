<?php
require BASE_PATH . 'vendor/autoload.php';

$mollie_customer = new Mollie_Customer;

class Mollie_Customer {

    public function createCustomer($name, $email, $user_id) {

        include BASE_PATH . 'software/managing/customer/payment/mollie/initialize.php';

        $customer = $mollie->customers->create([
            "name" => $name,
            "email" => $email,
        ]);

        $customer_id = $customer->id;

        $SQL = self::db()->prepare("UPDATE `customers` SET `mollie_customer_id` = :customer_id WHERE `id` = :user_id");
        $SQL->execute(array(":customer_id" => $customer_id, ":user_id" => $user_id));

        return $customer_id;
    }

    public function createMandat($customer_id, $name, $iban, $bic, $signatureDate) {
        include BASE_PATH . 'software/managing/customer/payment/mollie/initialize.php';

        $mandate = $mollie->customers->get($customer_id)->createMandate([
            "method" => \Mollie\Api\Types\MandateMethod::DIRECTDEBIT,
            "consumerName" => $name,
            "consumerAccount" => $iban,
            "consumerBic" => $bic,
            "signatureDate" => $signatureDate,
            "mandateReference" => "SchleyerEDV-MD" . Helper::generateRandomString('5'),
        ]);

        return $mandate;
    }

    public function createPayment($customer_id, $amount, $description, $redirectUrl, $webhookUrl = 'https://dev.portal.german-host.io/mollie_ipn.php') {
        include BASE_PATH . 'software/managing/customer/payment/mollie/initialize.php';

        $payment = $mollie->payments->create([
            "amount" => [
                "currency" => "EUR",
                "value" => $amount,
            ],
            "customerId" => $customer_id,
            "sequenceType" => "recurring",
            "description" => $description,
            "redirectUrl" => $redirectUrl,
            "webhookUrl" => $webhookUrl,
        ]);

        return $payment;
    }

    public function createSubscription($customer_id, $amount) {
        include BASE_PATH . 'software/managing/customer/payment/mollie/initialize.php';

        $customer = $mollie->customers->get($customer_id);
        $customer->createSubscription([
            "amount" => [
                "currency" => "EUR",
                "value" => $amount,
            ],
            "times" => 1,
            "interval" => "1 months",
            "sequenceType" => 'first',
            "description" => "Tescht",
            "webhookUrl" => 'https://dev.portal.german-host.io/mollie_ipn.php',
        ]);
    }
}