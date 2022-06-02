<?php
/*
 * Copyright (c) 2021, Bastian Leicht <mail@bastianleicht.de>
 *
 * PDX-License-Identifier: BSD-2-Clause
 */

namespace ResellerServices;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Http\Message\ResponseInterface;
use ResellerServices\Accounting\Account;
use ResellerServices\Domain\Nameserver;
use ResellerServices\Exception\ParameterException;
use ResellerServices\Domain\Domain;
use ResellerServices\License\Plesk;
use ResellerServices\VirtualServer\kvm;

class ResellerServices
{
    private $httpClient;
    private $credentials;
    private $apiToken;
    private $sandbox;

    /**
     * ResellerServices constructor.
     *
     * @param string    $token      API Token for all requests
     * @param bool      $sandbox    Enables the Sandbox Mode
     * @param null      $httpClient
     */
    public function __construct(
        string $token,
        bool $sandbox = false,
        $httpClient = null
    ) {
        $this->apiToken = $token;
        $this->sandbox = $sandbox;
        $this->setHttpClient($httpClient);
        $this->setCredentials($token, $sandbox);
    }

    /**
     * @param $httpClient Client|null
     */
    public function setHttpClient(Client $httpClient = null)
    {
        $this->httpClient = $httpClient ?: new Client([
            'allow_redirects' => false,
            'follow_redirects' => false,
            'timeout' => 120,
        ]);
    }

    public function setCredentials($credentials, $sandbox)
    {
        if (!$credentials instanceof Credentials) {
            $credentials = new Credentials($credentials, $sandbox);
        }

        $this->credentials = $credentials;
    }

    /**
     * @return Client
     */
    public function getHttpClient(): Client
    {
        return $this->httpClient;
    }

    /**
     * @return string
     */
    public function getToken()
    {
        return $this->apiToken;
    }

    /**
     * @return bool
     */
    public function isSandbox()
    {
        return $this->sandbox;
    }

    /**
     * @return Credentials
     */
    private function getCredentials(): Credentials
    {
        return $this->credentials;
    }

    /**
     * @param string    $actionPath The resource path you want to request, see more at the documentation.
     * @param array     $params     Array filled with request params
     * @param string    $method     HTTP method used in the request
     *
     * @return ResponseInterface
     * @throws GuzzleException
     *
     * @throws ParameterException If the given field in params is not an array
     */
    private function request(string $actionPath, array $params = [], string $method = 'GET'): ResponseInterface
    {
        $url = $this->getCredentials()->getUrl() . $actionPath;

        if (!is_array($params)) {
            throw new ParameterException();
        }

        $params['Authorization'] = 'Bearer ' . $this->apiToken;

        switch ($method) {
            case 'GET':
                return $this->getHttpClient()->get($url, [
                    'verify' => false,
                    'query'  => $params,
                ]);
            case 'POST':
                return $this->getHttpClient()->post($url, [
                    'verify' => false,
                    'headers'  => [
                        'Content-Type' => 'application/json',
                        'Accept' => 'application/json',
                        'Authorization' => 'Bearer ' . $this->apiToken,
                    ],
                    'form_params'   => $params,
                ]);
            case 'PUT':
                return $this->getHttpClient()->put($url, [
                    'verify' => false,
                    'headers'  => [
                        'Content-Type' => 'application/json',
                        'Accept' => 'application/json',
                        'Authorization' => 'Bearer ' . $this->apiToken,
                    ],
                    'form_params'   => $params,
                ]);
            case 'DELETE':
                return $this->getHttpClient()->delete($url, [
                    'verify' => false,
                    'headers'  => [
                        'Content-Type' => 'application/json',
                        'Accept' => 'application/json',
                        'Authorization' => 'Bearer ' . $this->apiToken,
                    ],
                    'form_params'   => $params,
                ]);
            default:
                throw new ParameterException('Wrong HTTP method passed');
        }
    }

    /**
     * @param $response ResponseInterface
     *
     * @return array|string
     */
    private function processRequest(ResponseInterface $response)
    {
        $response = $response->getBody()->__toString();
        $result = json_decode($response);
        if (json_last_error() == JSON_ERROR_NONE) {
            return $result;
        } else {
            return $response;
        }
    }

    /**
     * @throws GuzzleException
     */
    public function get($actionPath, $params = [])
    {
        $response = $this->request($actionPath, $params);

        return $this->processRequest($response);
    }

    /**
     * @throws GuzzleException
     */
    public function put($actionPath, $params = [])
    {
        $response = $this->request($actionPath, $params, 'PUT');

        return $this->processRequest($response);
    }

    /**
     * @throws GuzzleException
     */
    public function post($actionPath, $params = [])
    {
        $response = $this->request($actionPath, $params, 'POST');

        return $this->processRequest($response);
    }

    /**
     * @throws GuzzleException
     */
    public function delete($actionPath, $params = [])
    {
        $response = $this->request($actionPath, $params, 'DELETE');

        return $this->processRequest($response);
    }

    private $paymentHandler;
    private $pleskHandler;
    private $domainHandler;
    private $virtualServerHandler;
    private $accountingHandler;

    /**
     * @return Payment
     */
    public function payment(): Payment
    {
        if (!$this->paymentHandler) $this->paymentHandler = new Payment($this);
        return $this->paymentHandler;
    }

    /**
     * @return Plesk
     */
    public function plesk(): Plesk
    {
        if(!$this->pleskHandler) $this->pleskHandler = new Plesk($this);
        return $this->pleskHandler;
    }

    /**
     * @return Domain
     */
    public function domain(): Domain
    {
        if(!$this->domainHandler) $this->domainHandler = new Domain($this);
        return $this->domainHandler;
    }

    /**
     * @return kvm
     */
    public function virtualServer(): kvm
    {
        if(!$this->virtualServerHandler) $this->virtualServerHandler = new kvm($this);
        return $this->virtualServerHandler;
    }

    /**
     * @return Account
     */
    public function accounting(): Account
    {
        if(!$this->accountingHandler) $this->accountingHandler = new Account($this);
        return $this->accountingHandler;
    }


}
