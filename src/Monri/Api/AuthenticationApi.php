<?php

namespace Monri\Api;

use Monri\Config;
use Monri\HttpClient;

class AuthenticationApi
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
     * @var AccessTokens
     */
    protected $accessTokens;

    /**
     * @param Config $config
     * @param HttpClient $httpClient
     * @param AccessTokens $accessTokens
     */
    public function __construct(Config $config, HttpClient $httpClient, AccessTokens $accessTokens)
    {
        $this->config = $config;
        $this->httpClient = $httpClient;
        $this->accessTokens = $accessTokens;
    }


}