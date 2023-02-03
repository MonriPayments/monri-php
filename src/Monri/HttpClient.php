<?php

namespace Monri;

use Monri\Exception\MonriException;
use Monri\Exception\MonriHttpException;

class HttpClient
{

    /**
     * @var Config
     */
    private $config;

    /**
     * @param Config $config
     */
    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    public function get(string $url, array $headers): ApiHttpResponse
    {
        return $this->request('GET', $url, null, $headers);
    }

    public function post(string $url, array $body, array $headers): ApiHttpResponse
    {
        return $this->request('POST', $url, $body, $headers);
    }

    /**
     * @param string $method
     * @param string $url
     * @param array|null $body
     * @param array $request_headers
     * @return ApiHttpResponse
     */
    private function request(
        string $method,
        string $url,
        ?array $body = array(),
        array  $request_headers = array()
    ): ApiHttpResponse {
        $ch = curl_init($this->buildUrl($url));
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        if ($method == 'POST') {
            $data_string = json_encode($body);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
        }

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERAGENT, Client::USER_AGENT);

        if (isset($request_headers['oauth'])) {
            $token = $request_headers['oauth'];
            unset($request_headers['oauth']);
            $request_headers[] = "Authorization: Bearer $token";
        }

        $request_headers[] = 'Content-Type: application/json';
        $request_headers[] = 'Accept: application/json';
        $response_headers = [];
        curl_setopt($ch, CURLOPT_HTTPHEADER, $request_headers);
        $result = curl_exec($ch);
        $curl_err = curl_errno($ch);
        curl_setopt(
            $ch,
            CURLOPT_HEADERFUNCTION,
            function ($curl, $header) use (&$response_headers) {
                $len = strlen($header);
                $header = explode(':', $header, 2);
                if (count($header) < 2) { // ignore invalid headers
                    return $len;
                }

                $response_headers[strtolower(trim($header[0]))][] = trim($header[1]);

                return $len;
            }
        );
        $http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $response_as_json = self::convertBodyToJson($result);
        $exception = null;
        if (curl_errno($ch)) {
            $exception = new MonriException("Curl error=$$curl_err");
        }

        curl_close($ch);

        if (!($http_status >= 200 && $http_status < 300)) {
            if (!isset($exception)) {
                $exception = new MonriHttpException(
                    $http_status,
                    $result,
                    $response_headers,
                    "Request failed with status code='$http_status'"
                );
            }
        }

        return new \Monri\ApiHttpResponse($response_as_json, $result, $http_status, $response_headers, $exception);
    }

    private static function convertBodyToJson($response)
    {
        try {
            return json_decode($response, true);
        } catch (\Exception $exception) {
            return [];
        }
    }

    private function buildUrl($url): string
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        return $this->config->getBaseApiUrl() . $url;
    }
}
