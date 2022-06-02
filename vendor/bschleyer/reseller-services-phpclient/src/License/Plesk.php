<?php
/*
 * Copyright (c) 2021, Bastian Leicht <mail@bastianleicht.de>
 *
 * PDX-License-Identifier: BSD-2-Clause
 */

namespace ResellerServices\License;

use GuzzleHttp\Exception\GuzzleException;
use ResellerServices\Exception\AssertNotImplemented;
use ResellerServices\ResellerServices;

class Plesk
{
    private $resellerServices;

    public function __construct(ResellerServices $resellerServices)
    {
        $this->resellerServices = $resellerServices;
    }

    /**
     * @param bool $company
     * @return array|string
     * @throws GuzzleException
     */
    public function getPricelist(bool $company = false)
    {
        return $this->resellerServices->post('license/getPricelist', [
            'company' => $company,
        ]);
    }

    /**
     * @param string $license_type  PLSK_12_ADMIN_VPS | PLSK_12_PRO_VPS | PLSK_12_HOST_VPS | PLSK_12_ADMIN | PLSK_12_PRO | PLSK_12_HOST
     * @return array|string
     * @throws GuzzleException
     */
    public function order(string $license_type)
    {
        return $this->resellerServices->post('license/order', [
            'type' => $license_type
        ]);
    }

    /**
     * @param string $license_id    PLSK.00000000
     * @return array|string
     * @throws GuzzleException
     */
    public function reset(string $license_id)
    {
        return $this->resellerServices->post('license/reset', [
            'id' => $license_id
        ]);
    }

    /**
     * @param string $license_id    PLSK.00000000
     * @param string $ip_address    1.1.1.1
     * @return array|string
     * @throws GuzzleException
     */
    public function setIpBinding(string $license_id, string $ip_address)
    {
        if($this->resellerServices->isSandbox() === true) {
            throw new AssertNotImplemented();
        }
        return $this->resellerServices->post('license/setIpBinding', [
            'id' => $license_id,
            'ip' => $ip_address
        ]);
    }

    /**
     * @param string $license_id    PLSK.00000000
     * @return array|string
     * @throws GuzzleException
     */
    public function getIpBinding(string $license_id)
    {
        if($this->resellerServices->isSandbox() === true) {
            throw new AssertNotImplemented();
        }
        return $this->resellerServices->post('license/getIpBinding', [
            'id' => $license_id
        ]);
    }

    /**
     * @param string $license_id    PLSK.00000000
     * @param string $date          yyyy-mm-dd  (2021-10-12)
     * @return array|string
     * @throws GuzzleException
     */
    public function delete(string $license_id, string $date)
    {
        return $this->resellerServices->post('license/delete', [
            'id' => $license_id,
            'date' => $date
        ]);
    }

}