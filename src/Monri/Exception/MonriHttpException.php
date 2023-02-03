<?php

namespace Monri\Exception;

class MonriHttpException extends \Exception
{
    /**
     * @var integer
     */
    private $statusCode;
    /**
     * @var string
     */
    private $responseBody;
    /**
     * @var array
     */
    private $headers;

    /**
     * @param int $statusCode
     * @param string $responseBody
     * @param array $headers
     */
    public function __construct(int $statusCode, string $responseBody, array $headers, string $message)
    {
        parent::__construct($message);
        $this->statusCode = $statusCode;
        $this->responseBody = $responseBody;
        $this->headers = $headers;
    }

    /**
     * @return int
     */
    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    /**
     * @return string
     */
    public function getResponseBody(): string
    {
        return $this->responseBody;
    }

    /**
     * @return array
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }
}
