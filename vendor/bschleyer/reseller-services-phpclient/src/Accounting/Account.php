<?php
/*
 * Copyright (c) 2021, Bastian Leicht <mail@bastianleicht.de>
 *
 * PDX-License-Identifier: BSD-2-Clause
 */

namespace ResellerServices\Accounting;

use ResellerServices\ResellerServices;

class Account
{
    private $resellerServices;
    private $invoiceHandler;

    public function __construct(ResellerServices $resellerServices)
    {
        $this->resellerServices = $resellerServices;
    }

    /**
     * @return Invoice
     */
    public function invoice(): Invoice
    {
        if(!$this->invoiceHandler) $this->invoiceHandler = new Invoice($this->resellerServices);
        return $this->invoiceHandler;
    }

}