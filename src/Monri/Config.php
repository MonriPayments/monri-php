<?php

namespace Monri;

use Monri\Exception\MonriException;

class Config
{

    /**
     * @var string | null
     */
    private $merchantKey;
    /**
     * @var string | null
     */
    private $authenticityToken;
    /**
     * @var string | null
     */
    private $environment;

    /**
     * @param string|null $merchantKey
     * @param string|null $authenticityToken
     * @param string|null $environment
     */
    public function __construct(
        ?string $merchantKey = null,
        ?string $authenticityToken = null,
        ?string $environment = null
    ) {
        $this->merchantKey = $merchantKey;
        $this->authenticityToken = $authenticityToken;
        $this->setEnvironment($environment);
    }


    /**
     * @return bool
     */
    public function isConfigured(): bool
    {
        return isset($this->merchantKey) && isset($this->authenticityToken) && isset($this->environment);
    }

    /**
     * @return string|null
     */
    public function getMerchantKey(): ?string
    {
        return $this->merchantKey;
    }

    /**
     * @param string|null $merchantKey
     */
    public function setMerchantKey(?string $merchantKey): void
    {
        $this->merchantKey = $merchantKey;
    }

    /**
     * @return string|null
     */
    public function getAuthenticityToken(): ?string
    {
        return $this->authenticityToken;
    }

    /**
     * @param string|null $authenticityToken
     */
    public function setAuthenticityToken(?string $authenticityToken): void
    {
        $this->authenticityToken = $authenticityToken;
    }

    /**
     * @return string|null
     */
    public function getEnvironment(): ?string
    {
        return $this->environment;
    }

    /**
     * @param string|null $environment
     * @throws MonriException
     */
    public function setEnvironment(?string $environment): void
    {
        if (isset($environment)) {
            if (!($environment == 'test' || $environment == 'production')) {
                throw new MonriException("Environment='${environment}' not supported!");
            }
        }
        $this->environment = $environment;
    }

    /**
     * @return string
     * @throws MonriException
     */
    public function getBaseApiUrl(): string
    {
        if ($this->environment == 'test') {
            return 'https://ipgtest.monri.com';
        } elseif ($this->environment == 'production') {
            return 'https://ipg.monri.com';
        } else {
            throw new \Monri\Exception\MonriException("Environment=$this->environment not supported");
        }
    }
}
