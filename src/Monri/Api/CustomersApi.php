<?php

namespace Monri\Api;

use Monri\ApiHttpResponse;
use Monri\Exception\MonriException;
use Monri\Model\CustomerResponse;

class CustomersApi extends AuthenticationApi
{
    /**
     * @throws \Exception
     */
    public function create($params): CustomerResponse
    {
        $accessToken = $this->accessTokens->create(['scopes' => ['customers']])->getAccessToken();
        $response = $this->httpClient->post('/v2/customers', $params, ['oauth' => $accessToken]);
        if ($response->isFailed()) {
            throw $response->getException();
        } else {
            return self::createCustomer($response);
        }
    }

    /**
     * @throws MonriException
     * @throws \Exception
     */
    public function findByMerchantId($id): CustomerResponse
    {
        if (!is_string($id)) {
            throw new MonriException('Id should be a string');
        }

        $accessToken = $this->accessTokens->create(['scopes' => ['customers']])->getAccessToken();
        $response = $this->httpClient->get("/v2/merchants/customers/$id", ['oauth' => $accessToken]);
        if ($response->isFailed()) {
            throw $response->getException();
        } else {
            return self::createCustomer($response);
        }
    }

    /**
     * @throws MonriException
     */
    public function customer($id): CustomerApi
    {
        return new CustomerApi(
            $this->config,
            $this->httpClient,
            $this->accessTokens,
            $id
        );
    }

    /**
     * @param ApiHttpResponse $response
     * @return CustomerResponse
     */
    public static function createCustomer(ApiHttpResponse $response): CustomerResponse
    {
        $body = $response->getBody();
        return new CustomerResponse(
            $body['uuid'] ?? null,
            $body['merchant_customer_id'] ?? null,
            $body['description'] ?? null,
            $body['email'] ?? null,
            $body['name'] ?? null,
            $body['phone'] ?? null,
            $body['status'] ?? null,
            $body['deleted'] ?? null,
            $body['city'] ?? null,
            $body['country'] ?? null,
            $body['zip_code'] ?? null,
            $body['address'] ?? null,
            $body['metadata'] ?? null,
            $body['created_at'] ?? null,
            $body['updated_at'] ?? null,
            $body['deleted_at'] ?? null,
        );
    }
}
