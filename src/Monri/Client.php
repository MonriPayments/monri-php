<?php

namespace Monri;

use Monri\Api\AccessTokensApi;
use Monri\Api\CustomersApi;
use Monri\Api\PaymentsApi;
use Monri\Api\TokensApi;
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
     * @var PaymentsApi
     */
    private $payments;

    /**
     * @var AccessTokensApi
     */
    private $accessTokens;

    /**
     * @var CustomersApi
     */
    private $customers;

    /**
     * @var TokensApi
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
     * @return PaymentsApi
     * @throws MonriException if configuration is not set
     */
    public function payments(): PaymentsApi
    {
        $this->ensureConfigSet();
        if ($this->tokens == null) {
            $this->payments = new PaymentsApi($this->config, $this->httpClient(), $this->accessTokens());
        }
        return $this->payments;
    }

    /**
     * @return AccessTokensApi
     * @throws MonriException if configuration is not set
     */
    public function accessTokens(): AccessTokensApi
    {
        $this->ensureConfigSet();
        if ($this->tokens == null) {
            $this->accessTokens = new AccessTokensApi($this->config, $this->httpClient());
        }
        return $this->accessTokens;
    }

    /**
     * @return TokensApi
     * @throws MonriException if configuration is not set
     */
    public function tokens(): TokensApi
    {
        $this->ensureConfigSet();
        if ($this->tokens == null) {
            $this->tokens = new TokensApi($this->config, $this->httpClient());
        }
        return $this->tokens;
    }

    /**
     * @return CustomersApi
     * @throws MonriException if configuration is not set
     */
    public function customers(): CustomersApi
    {
        $this->ensureConfigSet();
        if ($this->tokens == null) {
            $this->customers = new CustomersApi($this->config, $this->httpClient(), $this->accessTokens());
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
