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

class kvmJobs
{
    private $resellerServices;

    public function __construct(ResellerServices $resellerServices)
    {
        $this->resellerServices = $resellerServices;
    }

    /**
     * @param string $job_id
     * @return array|string
     * @throws GuzzleException
     */
    public function get(string $job_id)
    {
        return $this->resellerServices->post('rootserver/job/get', [
            'job_id' => $job_id
        ]);
    }

}