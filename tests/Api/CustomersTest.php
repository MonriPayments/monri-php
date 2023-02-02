<?php

namespace Monri\Tests\Api;

use Monri\Api\Customers;
use Monri\Exception\MonriException;
use PHPUnit\Framework\TestCase;

class CustomersTest extends \Monri\Tests\TestCase
{

    /**
     * @throws MonriException
     * @throws \Exception
     */
    public function testCreate()
    {
        $id = random_int(100000, 999999) . "";
        $response = $this->client->customers()->create([
            'email' => 'email@email.com',
            'name' => 'name',
            'merchant_customer_id' => $id
        ]);
        $this->assertNotNull($response);
        $this->assertNotNull($response->getId());
        $this->assertNotNull($response->getEmail());
        $this->assertNotNull($response->getName());
        $this->assertEquals('approved', $response->getStatus());
    }

    /**
     * @throws MonriException
     * @throws \Exception
     */
    public function testFind()
    {
        $id = random_int(100000, 999999) . "";
        $response = $this->client->customers()->create([
            'email' => 'email@email.com',
            'name' => 'name',
            'merchant_customer_id' => $id
        ]);
        $this->assertNotNull($response);
        $this->assertNotNull($response->getId());
        $this->assertNotNull($response->getEmail());
        $this->assertNotNull($response->getName());
        $this->assertEquals('approved', $response->getStatus());

        $response = $this->client->customers()->find($response->getId());
        $this->assertNotNull($response);
        $this->assertNotNull($response->getId());
        $this->assertNotNull($response->getEmail());
        $this->assertNotNull($response->getName());
        $this->assertEquals('approved', $response->getStatus());

        $response = $this->client->customers()->findByMerchantId($id);
        $this->assertNotNull($response);
        $this->assertNotNull($response->getId());
        $this->assertNotNull($response->getEmail());
        $this->assertNotNull($response->getName());
        $this->assertEquals('approved', $response->getStatus());
        $this->assertEquals($id, $response->getMerchantCustomerId());
    }

    /**
     * @throws MonriException
     * @throws \Exception
     */
    public function testPaymentMethods()
    {
        $id = random_int(100000, 999999) . "";
        $response = $this->client->customers()->create([
            'email' => 'email@email.com',
            'name' => 'name',
            'merchant_customer_id' => $id
        ]);
        $this->assertNotNull($response);
        $this->assertNotNull($response->getId());
        $this->assertNotNull($response->getEmail());
        $this->assertNotNull($response->getName());
        $this->assertEquals('approved', $response->getStatus());

        $response = $this->client->customers()->find($response->getId());
        $this->assertNotNull($response);
        $this->assertNotNull($response->getId());
        $this->assertNotNull($response->getEmail());
        $this->assertNotNull($response->getName());
        $this->assertEquals('approved', $response->getStatus());

        $response = $this->client->customers()->paymentMethods($id);
        $this->assertNotNull($response);
        $this->assertNotNull($response->getData());
        $this->assertNotNull($response->getStatus());
        $this->assertTrue(is_array($response->getData()));
        $this->assertEquals('approved', $response->getStatus());
    }

    /**
     * @throws MonriException
     * @throws \Exception
     */
    public function testPaymentMethodsExistingCustomer()
    {
        // Existing customer
        $response = $this->client->customers()->find('cacf6ea5-cce5-4c25-86c2-493395641837');
        $this->assertNotNull($response);
        $this->assertNotNull($response->getId());
        $this->assertNotNull($response->getEmail());
        $this->assertNotNull($response->getName());
        $this->assertEquals('approved', $response->getStatus());

        $response = $this->client->customers()->paymentMethods($response->getId());
        $this->assertNotNull($response);
        $this->assertNotNull($response->getData());
        $this->assertNotNull($response->getStatus());
        $this->assertTrue(is_array($response->getData()));
        $this->assertEquals('approved', $response->getStatus());
    }
}
