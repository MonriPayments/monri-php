<?php

namespace Monri\Api;

use Monri\Config;
use Monri\Exception\MonriException;
use Monri\HttpClient;
use Monri\Model\AccessTokenCreateResponse;

class AccessTokensApi
{
    /**
     * @var HttpClient
     */
    private $httpClient;
    /**
     * @var Config
     */
    private $config;

    /**
     * @param Config $config
     * @param HttpClient $httpClient
     */
    public function __construct(Config $config, HttpClient $httpClient)
    {
        $this->httpClient = $httpClient;
        $this->config = $config;
    }


    /**
     * @throws \Exception
     */
    public function create($params): AccessTokenCreateResponse
    {
        $scopes = $params['scopes'] ?? [];
        if (!is_array($scopes)) {
            throw new MonriException("params['scopes'] should be an array");
        }
        $body = [
            'client_id' => $this->config->getAuthenticityToken(),
            'client_secret' => $this->config->getMerchantKey(),
            'grant_type' => 'client_credentials',
            'scopes' => $scopes
        ];
        $response = $this->httpClient->post('/v2/oauth', $body, []);
        if ($response->isFailed()) {
            throw $response->getException();
        } elseif ($response->isSuccess()) {
            $responseBody = $response->getBody();
            return new AccessTokenCreateResponse(
                $responseBody['access_token'],
                $responseBody['status'],
                $responseBody['token_type'],
                $responseBody['access_token'],
            );
        } else {
            throw new MonriException('Illegal state');
        }
    }

}