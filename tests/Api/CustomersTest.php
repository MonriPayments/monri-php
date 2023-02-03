<?php

namespace Monri\Tests\Api;

use Monri\Api\CustomersApi;
use Monri\Exception\MonriException;
use Monri\Exception\MonriHttpException;
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
        $this->assertCreatedCustomer($response);
    }

    /**
     * @throws MonriException
     * @throws \Exception
     */
    public function testCreateCreateException()
    {
        $id = random_int(100000, 999999) . "";
        $response = $this->client->customers()->create([
            'email' => 'email@email.com',
            'name' => 'name',
            'merchant_customer_id' => $id
        ]);

        $this->assertCreatedCustomer($response);

        $this->expectException(MonriHttpException::class);
        $this->expectErrorMessage("Request failed with status code='400'");

        $this->client->customers()->create([
            'email' => 'email@email.com',
            'name' => 'name',
            'merchant_customer_id' => $id
        ]);
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
        $this->assertCreatedCustomer($response);

        $response = $this->client->customers()->customer($response->getId())->details();
        $this->assertCreatedCustomer($response);

        $response = $this->client->customers()->findByMerchantId($id);
        $this->assertCreatedCustomer($response);
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
        $this->assertCreatedCustomer($response);

        $customerApi = $this->client->customers()->customer($response->getId());
        $response = $customerApi->details();
        $this->assertCreatedCustomer($response);

        $response = $this->client->customers()->customer($id)->paymentMethods();
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
        $response = $this->client->customers()->customer('cacf6ea5-cce5-4c25-86c2-493395641837')->details();
        $this->assertCreatedCustomer($response);

        $response = $this->client->customers()->customer($response->getId())->paymentMethods();
        $this->assertNotNull($response);
        $this->assertNotNull($response->getData());
        $this->assertNotNull($response->getStatus());
        $this->assertTrue(is_array($response->getData()));
        $this->assertEquals('approved', $response->getStatus());
    }

    /**
     * @param \Monri\Model\CustomerResponse $response
     * @return void
     */
    public function assertCreatedCustomer(\Monri\Model\CustomerResponse $response): void
    {
        $this->assertNotNull($response);
        $this->assertNotNull($response->getId());
        $this->assertNotNull($response->getEmail());
        $this->assertNotNull($response->getName());
        $this->assertEquals('approved', $response->getStatus());
    }
}
