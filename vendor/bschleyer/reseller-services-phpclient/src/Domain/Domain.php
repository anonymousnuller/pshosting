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

class Domain
{
    private $resellerServices;
    private $nameserverHandler;
    private $domainHandle;
    private $domainDNS;

    public function __construct(ResellerServices $resellerServices)
    {
        $this->resellerServices = $resellerServices;
    }

    /**
     * @return Nameserver
     */
    public function nameserver(): Nameserver
    {
        if(!$this->nameserverHandler) $this->nameserverHandler = new Nameserver($this->resellerServices);
        return $this->nameserverHandler;
    }

    /**
     * @return DomainHandle
     */
    public function handle(): DomainHandle
    {
        if(!$this->domainHandle) $this->domainHandle = new DomainHandle($this->resellerServices);
        return $this->domainHandle;
    }

    /**
     * @return DomainDNS
     */
    public function dns(): DomainDNS
    {
        if(!$this->domainDNS) $this->domainDNS = new DomainDNS($this->resellerServices);
        return $this->domainDNS;
    }

    /**
     * @param bool $company
     * @return array|string
     * @throws GuzzleException
     */
    public function getPricelist(bool $company = false)
    {
        if($this->resellerServices->isSandbox() === true) {
            throw new AssertNotImplemented();
        }
        return $this->resellerServices->post('domain/getPricelist', [
            'company' => $company
        ]);
    }

    /**
     * @param string $tld   de | eu | com | net etc...
     * @param bool $company
     * @return array|string
     * @throws GuzzleException
     */
    public function getPrice(string $tld, bool $company = false)
    {
        return $this->resellerServices->post('domain/getPrice', [
            'company' => $company,
            'tld' => $tld
        ]);
    }

    /**
     * @param string $domainName    domain.de
     * @return array|string
     * @throws GuzzleException
     */
    public function check(string $domainName)
    {
        return $this->resellerServices->post('domain/check', [
            'domainName' => $domainName
        ]);
    }

    /**
     * @param string $domainName    domain.de
     * @return array|string
     * @throws GuzzleException
     */
    public function getDomain(string $domainName)
    {
        return $this->resellerServices->post('domain/getDomain', [
            'domainName' => $domainName
        ]);
    }

    /**
     * @param string $domainName    domain.de
     * @return array|string
     * @throws GuzzleException
     */
    public function getAuthcode(string $domainName)
    {
        return $this->resellerServices->post('domain/getAuthcode', [
            'domainName' => $domainName
        ]);
    }

    /**
     * @param string $domainName            domain.de
     * @param string $ownerContact          TEST5
     * @param string $adminContact          TEST5
     * @param string $technicianContact     TEST5
     * @param string $zoneContact           TEST5
     * @param string $ns1                   ns1.reselling.network
     * @param string $ns2                   ns2.reselling.network
     * @param string $ns3                   ns3.reselling.network
     * @param string $ns4                   ns4.reselling.network
     * @param string $ns5                   ns5.reselling.network
     * @param int $years                    1
     * @return array|string
     * @throws GuzzleException
     */
    public function register(string $domainName, string $ownerContact, string $adminContact, string $technicianContact,
                             string $zoneContact, string $ns1, string $ns2, string $ns3, string $ns4, string $ns5, int $years)
    {
        return $this->resellerServices->post('domain/register', [
            'domainName' => $domainName,
            'ownerC' => $ownerContact,
            'adminC' => $adminContact,
            'techC' => $technicianContact,
            'zoneC' => $zoneContact,
            'ns1' => $ns1,
            'ns2' => $ns2,
            'ns3' => $ns3,
            'ns4' => $ns4,
            'ns5' => $ns5,
            'years' => $years
        ]);
    }

    /**
     * @param string $domainName            domain.de
     * @param string $ownerContact          TEST5
     * @param string $adminContact          TEST5
     * @param string $technicianContact     TEST5
     * @param string $zoneContact           TEST5
     * @param string $ns1                   ns1.reselling.network
     * @param string $ns2                   ns2.reselling.network
     * @param string $ns3                   ns3.reselling.network
     * @param string $ns4                   ns4.reselling.network
     * @param string $ns5                   ns5.reselling.network
     * @param int $years                    1
     * @param string $authCode              test1234
     * @return array|string
     * @throws GuzzleException
     */
    public function transfer(string $domainName, string $ownerContact, string $adminContact, string $technicianContact, string $zoneContact,
                             string $ns1, string $ns2, string $ns3, string $ns4, string $ns5, int $years, string $authCode)
    {
        if($this->resellerServices->isSandbox() === true) {
            throw new AssertNotImplemented();
        }
        return $this->resellerServices->post('domain/transfer', [
            'domainName' => $domainName,
            'ownerC' => $ownerContact,
            'adminC' => $adminContact,
            'techC' => $technicianContact,
            'zoneC' => $zoneContact,
            'ns1' => $ns1,
            'ns2' => $ns2,
            'ns3' => $ns3,
            'ns4' => $ns4,
            'ns5' => $ns5,
            'years' => $years,
            'authCode' => $authCode
        ]);
    }

    /**
     * @param string $domainName            domain.de
     * @param string $ownerContact          TEST5
     * @param string $adminContact          TEST5
     * @param string $technicianContact     TEST5
     * @param string $zoneContact           TEST5
     * @param string $ns1                   ns1.reselling.network
     * @param string $ns2                   ns2.reselling.network
     * @param string $ns3                   ns3.reselling.network
     * @param string $ns4                   ns4.reselling.network
     * @param string $ns5                   ns5.reselling.network
     * @return array|string
     * @throws GuzzleException
     */
    public function update(string $domainName, string $ownerContact, string $adminContact, string $technicianContact,
                           string $zoneContact, string $ns1, string $ns2, string $ns3, string $ns4, string $ns5)
    {
        return $this->resellerServices->post('domain/update', [
            'domainName' => $domainName,
            'ownerC' => $ownerContact,
            'adminC' => $adminContact,
            'techC' => $technicianContact,
            'zoneC' => $zoneContact,
            'ns1' => $ns1,
            'ns2' => $ns2,
            'ns3' => $ns3,
            'ns4' => $ns4,
            'ns5' => $ns5
        ]);
    }

    /**
     * @param string $domainName test.de
     * @return array|string
     * @throws GuzzleException
     */
    public function restore(string $domainName)
    {
        return $this->resellerServices->post('domain/restore', [
            'domainName' => $domainName,
        ]);
    }

    /**
     * @param string $domainName    domain.de
     * @param string $date          yyyy-mm-dd  (2021-10-12)
     * @return array|string
     * @throws GuzzleException
     */
    public function delete(string $domainName, string $date)
    {
        return $this->resellerServices->post('domain/delete', [
            'domainName' => $domainName,
            'date' => $date
        ]);
    }

    /**
     * @param string $domainName    domain.de
     * @return array|string
     * @throws GuzzleException
     */
    public function undelete(string $domainName)
    {
        return $this->resellerServices->post('domain/undelete', [
            'domainName' => $domainName
        ]);
    }

}