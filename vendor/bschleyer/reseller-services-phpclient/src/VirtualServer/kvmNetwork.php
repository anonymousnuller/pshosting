<?php
/*
 * Copyright (c) 2021, Bastian Leicht <mail@bastianleicht.de>
 *
 * PDX-License-Identifier: BSD-2-Clause
 */

namespace ResellerServices\VirtualServer;

use GuzzleHttp\Exception\GuzzleException;
use ResellerServices\Exception\AssertNotImplemented;
use ResellerServices\ResellerServices;

class kvmNetwork
{
    private $resellerServices;

    public function __construct(ResellerServices $resellerServices)
    {
        $this->resellerServices = $resellerServices;
    }

    /**
     * @param string $server_id
     * @param string $ip_address
     * @param string $rdns
     * @return array|string
     * @throws GuzzleException
     */
    public function setRDNS(string $server_id, string $ip_address, string $rdns)
    {
        if($this->resellerServices->isSandbox() === true) {
            throw new AssertNotImplemented();
        }
        return $this->resellerServices->post('rootserver/ip/setRDNS', [
            'vm_id' => $server_id,
            'ip' => $ip_address,
            'rdns' => $rdns
        ]);
    }

}