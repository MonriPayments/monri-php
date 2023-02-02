<?php

namespace Monri;

use Monri\Api\AccessTokens;
use Monri\Api\Customers;
use Monri\Api\Payments;
use Monri\Api\Tokens;
use Monri\Exception\MonriException;
use TheSeer\Tokenizer\Token;

class Client
{

    const VERSION = '1.0.0';
    const USER_AGENT = "Monri/PHP/" . self::VERSION;

    /**
     * @var Config
     */
    private $config;

    /**
     * @var Payments
     */
    private $payments;

    /**
     * @var AccessTokens
     */
    private $accessTokens;

    /**
     * @var Customers
     */
    private $customers;

    /**
     * @var Tokens
     */
    private $tokens;

    /**
     * @var HttpClient
     */
    private $httpClient;


    public function __construct(Config $config = null)
    {
        if ($config == null) {
            $this->config = new Config();
        } elseif (!($config instanceof Config)) {
            /** @noinspection PhpUnhandledExceptionInspection */
            /** @noinspection PhpUnnecessaryFullyQualifiedNameInspection */
            throw new \Monri\Exception\MonriException('Config object not an instance of Config');
        } else {
            $this->config = $config;
        }
    }

    /**
     * @return Payments
     * @throws MonriException if configuration is not set
     */
    public function payments(): Payments
    {
        $this->ensureConfigSet();
        if ($this->tokens == null) {
            $this->payments = new Payments($this->config, $this->httpClient(), $this->accessTokens());
        }
        return $this->payments;
    }

    /**
     * @return AccessTokens
     * @throws MonriException if configuration is not set
     */
    public function accessTokens(): AccessTokens
    {
        $this->ensureConfigSet();
        if ($this->tokens == null) {
            $this->accessTokens = new AccessTokens($this->config, $this->httpClient());
        }
        return $this->accessTokens;
    }

    /**
     * @return Tokens
     * @throws MonriException if configuration is not set
     */
    public function tokens(): Tokens
    {
        $this->ensureConfigSet();
        if ($this->tokens == null) {
            $this->tokens = new Tokens($this->config, $this->httpClient());
        }
        return $this->tokens;
    }

    /**
     * @return Customers
     * @throws MonriException if configuration is not set
     */
    public function customers(): Customers
    {
        $this->ensureConfigSet();
        if ($this->tokens == null) {
            $this->customers = new Customers($this->config, $this->httpClient(), $this->accessTokens());
        }
        return $this->customers;
    }

    /**
     * @param string|null $merchantKey
     */
    public function setMerchantKey(?string $merchantKey)
    {
        $this->config->setMerchantKey($merchantKey);
    }

    /**
     * @param string | null $authenticityToken
     */
    public function setAuthenticityToken(?string $authenticityToken)
    {
        $this->config->setAuthenticityToken($authenticityToken);
    }

    /**
     * @param string | null $environment
     */
    public function setEnvironment(?string $environment)
    {
        $this->config->setEnvironment($environment);
    }

    /**
     * @throws MonriException
     */
    private function ensureConfigSet()
    {
        if (!$this->config->isConfigured()) {
            throw new MonriException('Configuration not set!');
        }
    }

    /**
     * @throws MonriException
     */
    private function httpClient(): HttpClient
    {
        $this->ensureConfigSet();
        if (!isset($this->httpClient)) {
            $this->httpClient = new HttpClient($this->config);
        }
        return $this->httpClient;
    }

    /**
     * @return string|null
     */
    public function getMerchantKey(): ?string
    {
        return $this->config->getMerchantKey();
    }

    /**
     * @return string|null
     */
    public function getAuthenticityToken(): ?string
    {
        return $this->config->getAuthenticityToken();
    }

    /**
     * @return string|null
     */
    public function getEnvironment(): ?string
    {
        return $this->config->getEnvironment();
    }
}
