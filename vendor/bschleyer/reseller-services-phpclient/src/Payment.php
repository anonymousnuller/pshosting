<?php
/*
 * Copyright (c) 2021, Bastian Leicht <mail@bastianleicht.de>
 *
 * PDX-License-Identifier: BSD-2-Clause
 */

namespace ResellerServices;

use GuzzleHttp\Exception\GuzzleException;

class Payment
{
    private $resellerServices;

    public function __construct(ResellerServices $resellerServices)
    {
        $this->resellerServices = $resellerServices;
    }

    /**
     * @param string $payment_method    SEPA | paysafecard | SOFORT | PAYPAL | GIROPAY | CREDITCARD | APPLEPAY
     * @param float $amount             1.00
     * @param string $success_url       https://interface.reseller-services.de/dashboard
     * @param string $failure_url       https://interface.reseller-services.de/dashboard
     * @param string|null $startPayment
     * @return array|string
     * @throws GuzzleException
     */
    public function create(string $payment_method, float $amount, string $success_url, string $failure_url, string $startPayment = null)
    {
        return $this->resellerServices->post('payment/create', [
            'startPayment' => $startPayment,
            'payment_method' => $payment_method,
            'amount' => $amount,
            'success_url' => $success_url,
            'failure_url' => $failure_url
        ]);
    }

    /**
     * @param string $transaction_id    tr_yJ7GD6Mh3D
     * @return array|string
     * @throws GuzzleException
     */
    public function validate(string $transaction_id)
    {
        return $this->resellerServices->post('payment/check', [
            'tid' => $transaction_id
        ]);
    }
}