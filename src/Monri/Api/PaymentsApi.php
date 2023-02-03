<?php

namespace Monri\Api;

use Monri\Model\PaymentCreateResponse;
use Monri\Model\PaymentResult;
use Monri\Model\StatusResponse;

class PaymentsApi extends AuthenticationApi
{
    /**
     * @throws \Exception
     */
    public function create($params): PaymentCreateResponse
    {
        $accessToken = $this->accessTokens->create(['scopes' => ['payments']])->getAccessToken();
        $response = $this->httpClient->post('/v2/payment/new', $params, ['oauth' => $accessToken]);
        if ($response->isFailed()) {
            throw $response->getException();
        } else {
            $body = $response->getBody();
            return new PaymentCreateResponse(
                $body['id'],
                $body['client_secret'],
                $body['status']
            );
        }
    }

    /**
     * @throws \Exception
     */
    public function status($id): StatusResponse
    {
        $accessToken = $this->accessTokens->create(['scopes' => ['payments']])->getAccessToken();
        $response = $this->httpClient->get("/v2/payment/$id/status", ['oauth' => $accessToken]);
        if ($response->isFailed()) {
            throw $response->getException();
        } else {
            $body = $response->getBody();
            return new StatusResponse(
                self::paymentResult($body['payment_result']),
                $body['status'],
                $body['payment_status'],
                $body['client_secret'],
            );
        }
    }

    private static function paymentResult($result): PaymentResult
    {
        return new PaymentResult(
            $result['currency'],
            $result['amount'],
            $result['order_number'],
            $result['created_at'],
            $result['status'],
            $result['transaction_type'],
            $result['payment_method'],
            $result['response_message']
        );
    }

}