<?php

namespace Monri\Tests;

use Monri\Exception\MonriException;

class ClientTest extends \Monri\Tests\TestCase
{

    /**
     * @throws MonriException
     */
    public function testTokens()
    {
        $this->assertNotNull($this->client->tokens());
    }

    public function testSetMerchantKey()
    {
        $this->client->setMerchantKey('tkn');
        $this->assertEquals('tkn', $this->client->getMerchantKey());
    }

    public function testSetAuthenticityToken()
    {
        $this->client->setAuthenticityToken('tkn');
        $this->assertEquals('tkn', $this->client->getAuthenticityToken());
    }

    public function testSetEnvironment()
    {
        $this->expectException(MonriException::class);
        $this->expectErrorMessage("Environment='tkn' not supported!");
        $this->client->setEnvironment('tkn');
    }

    public function testPayments()
    {
        $this->assertNotNull($this->client->payments());
    }

    public function testAccessTokens()
    {
        $this->assertNotNull($this->client->accessTokens());
    }

    public function testGetEnvironment()
    {
        $this->assertNotNull($this->client->getEnvironment());
    }

    public function testCustomers()
    {
        $this->assertNotNull($this->client->customers());
    }

    public function testGetMerchantKey()
    {
        $this->assertNotNull($this->client->getMerchantKey());
    }
}
