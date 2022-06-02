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

class kvmBackup
{
    private $resellerServices;

    public function __construct(ResellerServices $resellerServices)
    {
        $this->resellerServices = $resellerServices;
    }

    /**
     * @param string $server_id
     * @return array|string
     * @throws GuzzleException
     */
    public function list(string $server_id)
    {
        if($this->resellerServices->isSandbox() === true) {
            throw new AssertNotImplemented();
        }
        return $this->resellerServices->post('rootserver/backups/list', [
            'vm_id' => $server_id
        ]);
    }

    /**
     * @param string $server_id
     * @return array|string
     * @throws GuzzleException
     */
    public function create(string $server_id)
    {
        if($this->resellerServices->isSandbox() === true) {
            throw new AssertNotImplemented();
        }
        return $this->resellerServices->post('rootserver/backups/create', [
            'vm_id' => $server_id
        ]);
    }

    /**
     * @param string $server_id
     * @param string $backup
     * @return array|string
     * @throws GuzzleException
     */
    public function restore(string $server_id, string $backup)
    {
        if($this->resellerServices->isSandbox() === true) {
            throw new AssertNotImplemented();
        }
        return $this->resellerServices->post('rootserver/backups/restore', [
            'vm_id' => $server_id,
            'backup' => $backup
        ]);
    }

    /**
     * @param string $server_id
     * @return array|string
     * @throws GuzzleException
     */
    public function status(string $server_id)
    {
        if($this->resellerServices->isSandbox() === true) {
            throw new AssertNotImplemented();
        }
        return $this->resellerServices->post('rootserver/backups/status', [
            'vm_id' => $server_id
        ]);
    }

}