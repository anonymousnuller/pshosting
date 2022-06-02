<?php
/*
 * Copyright (c) 2021, Bastian Leicht <mail@bastianleicht.de>
 *
 * PDX-License-Identifier: BSD-2-Clause
 */

namespace ResellerServices\Domain;

use GuzzleHttp\Exception\GuzzleException;
use ResellerServices\Exception\AssertNotImplemented;
use ResellerServices\ResellerServices;

class DomainDNS
{
    private $resellerServices;

    public function __construct(ResellerServices $resellerServices)
    {
        $this->resellerServices = $resellerServices;
    }

    /**
     * @param string $domainName
     * @return array|string
     * @throws GuzzleException
     */
    public function get(string $domainName)
    {
        return $this->resellerServices->post('domain/dns/get', [
            'domainName' => $domainName
        ]);
    }

    /**
     * @param string $domainName
     * @param array $records
     * @return array|string
     * @throws GuzzleException
     */
    public function update(string $domainName, array $records)
    {
        return $this->resellerServices->post('domain/dns/update', [
            'domainName' => $domainName,
            'records' => $records
        ]);
    }

}