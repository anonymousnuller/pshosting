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

class kvm
{
    private $resellerServices;
    private $kvmBackupHandler;
    private $kvmNetworkHandler;
    private $kvmJobHandler;

    public function __construct(ResellerServices $resellerServices)
    {
        $this->resellerServices = $resellerServices;
    }

    /**
     * @return kvmBackup
     */
    public function backup(): kvmBackup
    {
        if(!$this->kvmBackupHandler) $this->kvmBackupHandler = new kvmBackup($this->resellerServices);
        return $this->kvmBackupHandler;
    }

    /**
     * @return kvmNetwork
     */
    public function network(): kvmNetwork
    {
        if(!$this->kvmNetworkHandler) $this->kvmNetworkHandler = new kvmNetwork($this->resellerServices);
        return $this->kvmNetworkHandler;
    }

    /**
     * @return kvmJobs
     */
    public function job(): kvmJobs
    {
        if(!$this->kvmJobHandler) $this->kvmJobHandler = new kvmJobs($this->resellerServices);
        return $this->kvmJobHandler;
    }

    /**
     * @return array|string
     * @throws GuzzleException
     */
    public function getTemplates()
    {
        if($this->resellerServices->isSandbox() === true) {
            throw new AssertNotImplemented();
        }
        return $this->resellerServices->post('rootserver/os/get', [ ]);
    }

    /**
     * @param string $server_id
     * @return array|string
     * @throws GuzzleException
     */
    public function getDetails(string $server_id)
    {
        if($this->resellerServices->isSandbox() === true) {
            throw new AssertNotImplemented();
        }
        return $this->resellerServices->post('rootserver/getDetails', [
            'vm_id' => $server_id
        ]);
    }

    /**
     * @param string $server_id
     * @return array|string
     * @throws GuzzleException
     */
    public function getStatus(string $server_id)
    {
        if($this->resellerServices->isSandbox() === true) {
            throw new AssertNotImplemented();
        }
        return $this->resellerServices->post('rootserver/getStatus', [
            'vm_id' => $server_id
        ]);
    }

    /**
     * @param string $server_id
     * @return array|string
     * @throws GuzzleException
     */
    public function getConfig(string $server_id)
    {
        if($this->resellerServices->isSandbox() === true) {
            throw new AssertNotImplemented();
        }
        return $this->resellerServices->post('rootserver/getConfig', [
            'vm_id' => $server_id
        ]);
    }

    /**
     * @param int $cores
     * @param int $memory   In Megabytes
     * @param int $disk     in Gigabytes
     * @param int $ips
     * @param string $os
     * @return array|string
     * @throws GuzzleException
     */
    public function order(int $cores, int $memory, int $disk, int $ips, string $os)
    {
        if($this->resellerServices->isSandbox() === true) {
            throw new AssertNotImplemented();
        }
        return $this->resellerServices->post('rootserver/order', [
            'cores' => $cores,
            'memory' => $memory,
            'disk' => $disk,
            'ips' => $ips,
            'os' => $os
        ]);
    }

    /**
     * @param string $server_id
     * @param int $cores
     * @param int $memory
     * @param int $disk
     * @param int $ips
     * @return array|string
     * @throws GuzzleException
     */
    public function change(string $server_id, int $cores, int $memory, int $disk, int $ips)
    {
        if($this->resellerServices->isSandbox() === true) {
            throw new AssertNotImplemented();
        }
        //TODO: Server id nicht in API?
        return $this->resellerServices->post('rootserver/change', [
            'vm_id' => $server_id,
            'cores' => $cores,
            'memory' => $memory,
            'disk' => $disk,
            'ips' => $ips
        ]);
    }

    /**
     * @param string $server_id
     * @param bool $force_delete
     * @return array|string
     * @throws GuzzleException
     */
    public function delete(string $server_id, bool $force_delete = false)
    {
        if($this->resellerServices->isSandbox() === true) {
            throw new AssertNotImplemented();
        }
        return $this->resellerServices->post('rootserver/delete', [
            'vm_id' => $server_id,
            'force' => $force_delete
        ]);
    }

    /**
     * @param string $server_id
     * @return array|string
     * @throws GuzzleException
     */
    public function start(string $server_id)
    {
        if($this->resellerServices->isSandbox() === true) {
            throw new AssertNotImplemented();
        }
        return $this->resellerServices->post('rootserver/manage/start', [
            'vm_id' => $server_id
        ]);
    }

    /**
     * @param string $server_id
     * @return array|string
     * @throws GuzzleException
     */
    public function shutdown(string $server_id)
    {
        if($this->resellerServices->isSandbox() === true) {
            throw new AssertNotImplemented();
        }
        return $this->resellerServices->post('rootserver/manage/shutdown', [
            'vm_id' => $server_id
        ]);
    }

    /**
     * @param string $server_id
     * @return array|string
     * @throws GuzzleException
     */
    public function stop(string $server_id)
    {
        if($this->resellerServices->isSandbox() === true) {
            throw new AssertNotImplemented();
        }
        return $this->resellerServices->post('rootserver/manage/stop', [
            'vm_id' => $server_id
        ]);
    }

    /**
     * @param string $server_id
     * @return array|string
     * @throws GuzzleException
     */
    public function reboot(string $server_id)
    {
        if($this->resellerServices->isSandbox() === true) {
            throw new AssertNotImplemented();
        }
        return $this->resellerServices->post('rootserver/manage/reboot', [
            'vm_id' => $server_id
        ]);
    }

    /**
     * @param string $server_id
     * @param string $server_os
     * @return array|string
     * @throws GuzzleException
     */
    public function reinstall(string $server_id, string $server_os)
    {
        if($this->resellerServices->isSandbox() === true) {
            throw new AssertNotImplemented();
        }
        return $this->resellerServices->post('rootserver/manage/reinstall', [
            'vm_id' => $server_id,
            'os' => $server_os
        ]);
    }

    /**
     * @param string $server_id
     * @return array|string
     * @throws GuzzleException
     */
    public function resetPassword(string $server_id)
    {
        if($this->resellerServices->isSandbox() === true) {
            throw new AssertNotImplemented();
        }
        return $this->resellerServices->post('rootserver/manage/reset', [
            'vm_id' => $server_id
        ]);
    }

    /**
     * @param string $server_id
     * @return array|string
     * @throws GuzzleException
     */
    public function noVNC(string $server_id)
    {
        if($this->resellerServices->isSandbox() === true) {
            throw new AssertNotImplemented();
        }
        return $this->resellerServices->post('rootserver/console/get', [
            'vm_id' => $server_id
        ]);
    }

}