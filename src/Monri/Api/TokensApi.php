<?php

namespace Monri\Api;

use Monri\Config;
use Monri\HttpClient;
use Monri\Model\TempCardId;

class TokensApi
{
    /**
     * @var Config
     */
    protected $config;
    /**
     * @var HttpClient
     */
    protected $httpClient;

    /**
     * @param Config $config
     * @param HttpClient $httpClient
     */
    public function __construct(Config $config, HttpClient $httpClient)
    {
        $this->config = $config;
        $this->httpClient = $httpClient;
    }


    /**
     * @throws \Exception
     */
    public function generateToken(?string $id = null): TempCardId
    {
        $id = $id ?? random_int(1000000, 9999999) . "";
        $timestamp = time();
        $digest = hash('sha512', $this->config->getMerchantKey() . $id . $timestamp);

        return new TempCardId($timestamp, $id, $digest);
    }
}
