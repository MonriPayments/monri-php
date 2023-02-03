<?php

namespace Monri\Tests\Api;

use Monri\Api\TokensApi;
use Monri\Exception\MonriException;

class TokensTest extends \Monri\Tests\TestCase
{

    /**
     * @throws MonriException
     * @throws \Exception
     */
    public function testCreateEphemeralCardToken()
    {
        $token = $this->client->tokens()->generateToken();
        $this->assertNotNull($token->getId());
        $this->assertNotNull($token->getDigest());
        $this->assertNotNull($token->getTimestamp());
    }

    /**
     * @throws MonriException
     * @throws \Exception
     */
    public function testCreateEphemeralCardTokenWithId()
    {
        $id = random_int(1000000, 9999999) . "";
        $token = $this->client->tokens()->generateToken($id);
        $this->assertNotNull($token->getDigest());
        $this->assertNotNull($token->getTimestamp());
        $this->assertEquals($id, $token->getId());
    }
}
