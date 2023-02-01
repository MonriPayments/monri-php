<?php

namespace Monri\Api;

use Monri\ApiHttpResponse;
use Monri\Exception\MonriException;
use Monri\Model\CustomerResponse;
use Monri\Model\PaymentMethodsResponse;

class Customers extends AuthenticationApi
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
            return $this->createCustomer($response);
        }
    }

    /**
     * @throws MonriException
     * @throws \Exception
     */
    public function find($id): CustomerResponse
    {
        if (!is_string($id)) {
            throw new MonriException('Id should be a string');
        }

        $accessToken = $this->accessTokens->create(['scopes' => ['customers']])->getAccessToken();
        $response = $this->httpClient->get("/v2/customers/$id", ['oauth' => $accessToken]);
        if ($response->isFailed()) {
            throw $response->getException();
        } else {
            return $this->createCustomer($response);
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
            return $this->createCustomer($response);
        }
    }

    /**
     * @throws MonriException
     */
    public function paymentMethods($id): PaymentMethodsResponse
    {
        if (!is_string($id)) {
            throw new MonriException('Id should be a string');
        }

        $accessToken = $this->accessTokens->create(['scopes' => ['customers', 'payment-methods']])->getAccessToken();
        $response = $this->httpClient->get("/v2/customers/$id/payment-methods", ['oauth' => $accessToken]);
        if ($response->isFailed()) {
            throw $response->getException();
        } else {
            return new PaymentMethodsResponse($response->getBody()['data'], $response->getBody()['status']);
        }
    }


    /**
     * @param ApiHttpResponse $response
     * @return CustomerResponse
     */
    private function createCustomer(ApiHttpResponse $response): CustomerResponse
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
