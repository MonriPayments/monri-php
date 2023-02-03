<?php

namespace Monri\Api;

use Monri\Config;
use Monri\Exception\MonriException;
use Monri\HttpClient;
use Monri\Model\CustomerResponse;
use Monri\Model\PaymentMethod;
use Monri\Model\PaymentMethodsResponse;

class CustomerApi extends AuthenticationApi
{
    /**
     * @var
     */
    private $id;

    /**
     * @param Config $config
     * @param HttpClient $httpClient
     * @param AccessTokensApi $accessTokens
     * @param string $id
     * @throws MonriException
     */
    public function __construct(
        Config          $config,
        HttpClient      $httpClient,
        AccessTokensApi $accessTokens,
        string          $id
    )
    {
        parent::__construct($config, $httpClient, $accessTokens);
        if (!is_string($id)) {
            throw new MonriException('Id should be a string');
        }
        $this->id = $id;
    }

    /**
     * @throws MonriException
     * @throws \Exception
     */
    public function details(): CustomerResponse
    {
        $accessToken = $this->accessTokens->create(['scopes' => ['customers']])->getAccessToken();
        $response = $this->httpClient->get("/v2/customers/$this->id", ['oauth' => $accessToken]);
        if ($response->isFailed()) {
            throw $response->getException();
        } else {
            return CustomersApi::createCustomer($response);
        }
    }

    /**
     * @throws MonriException
     * @throws \Exception
     */
    public function paymentMethods(): PaymentMethodsResponse
    {
        $accessToken = $this->accessTokens->create(['scopes' => ['customers', 'payment-methods']])->getAccessToken();
        $response = $this->httpClient->get("/v2/customers/$this->id/payment-methods", ['oauth' => $accessToken]);
        if ($response->isFailed()) {
            throw $response->getException();
        } else {
            $body = $response->getBody();
            $data = $body['data'] ?? [];
            $mapped = array_map('Monri\Api\CustomerApi::mapPaymentMethod', $data);
            return new PaymentMethodsResponse($mapped, $body['status'] ?? 'invalid-request');
        }
    }

    private static function mapPaymentMethod($pm): PaymentMethod
    {
        return new PaymentMethod(
            $pm['status'] ?? null,
            $pm['id'] ?? null,
            $pm['masked_pan'] ?? null,
            $pm['expiration_date'] ?? null,
            $pm['keep_until'] ?? null,
            $pm['created_at'] ?? null,
            $pm['updated_at'] ?? null,
            $pm['customer_uuid'] ?? null,
            $pm['token'] ?? null,
            $pm['expired'] ?? null
        );
    }
}
