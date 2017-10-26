<?php

require_once(__DIR__."/../src/config.php");
require_once(__DIR__."/../src/ResponseException.php");
require_once(__DIR__."/../src/services/Subscriptions.php");
require_once(__DIR__."/../src/services/Customers.php");
require_once(__DIR__."/../src/services/Tokens.php");

use PHPUnit\Framework\TestCase;
use Paydock\Sdk\config;
use Paydock\Sdk\Subscriptions;
use Paydock\Sdk\Customers;
use Paydock\Sdk\Tokens;
use Paydock\Sdk\ResponseException;

/**
 * @covers Subscriptions
 */
final class TestSubscriptions extends TestCase
{
    protected function setUp()
    {
        Config::initialise("sandbox", "fccbf57c8a65a609ed86edd417177905bfd5a99b", "cc5bedb53a1b64491b5b468a2486b32cc250cda2");
    }

    public function testCreateWithCard()
    {
        $svc = new Subscriptions();
        $response = $svc->create(100, "AUD")
            ->withCreditCard("58377235377aea03343240cc", "4111111111111111", "2020", "10", "Test Name", "123")
            ->withSchedule("month", 1)
            ->call();
        
        $this->assertEquals("201", $response["status"]);
    }
    
    public function testCreateWithBankAccount()
    {
        $svc = new Subscriptions();
        $response = $svc->create(100, "AUD")
            ->withBankAccount("58814949ca63b81cbd2acad0", "test", "012003", "456456")
            ->includeCustomerDetails("John", "Smith", "test@email.com", "+61414111111")
            ->withSchedule("month", 1)
            ->call();
        
        $this->assertEquals("201", $response["status"]);
    }
    
    public function testCreateWithCustomerId()
    {
        $custSvc = new Customers();
        $response = $custSvc->create("John", "Smith")
            ->withCreditCard("58377235377aea03343240cc", "4111111111111111", "2020", "10", "Test Name", "123")
            ->call();

        $customerId = $response["resource"]["data"]["_id"];

        $subscriptionSvc = new Subscriptions();
        $response = $subscriptionSvc->create(10, "AUD")
            ->withCustomerId($customerId)
            ->withSchedule("month", 1)
            ->call();

        $this->assertEquals("201", $response["status"]);
    }

    public function testCreateWithToken()
    {
        $svc = new Tokens();
        $response = $svc->create("John", "Smith")
            ->withCreditCard("58377235377aea03343240cc", "4111111111111111", "2020", "10", "Test Name", "123")
            ->call();

        $tokenId = $response["resource"]["data"];
        
        $subscriptionSvc = new Subscriptions();
        $response = $subscriptionSvc->create(10, "AUD")
            ->withToken($tokenId)
            ->withSchedule("month", 1)
            ->call();

        $this->assertEquals("201", $response["status"]);
    }
    
    public function testUpdate()
    {
        $svc = new Subscriptions();
        $response = $svc->create(100, "AUD")
            ->withCreditCard("58377235377aea03343240cc", "4111111111111111", "2020", "10", "Test Name", "123")
            ->withSchedule("month", 1)
            ->call();

        $response = $svc->update($response["resource"]["data"]["_id"], 101)
            ->withSchedule("month", 1)
            ->call();
        
        $this->assertEquals("200", $response["status"]);
        $this->assertEquals(101, $response["resource"]["data"]["amount"]);
    }
    
    public function testGet()
    {
        $svc = new Subscriptions();
        $response = $svc->get()
            ->call();
        
        $this->assertEquals("200", $response["status"]);
    }
    
    public function testGetWithId()
    {
        $svc = new Subscriptions();
        $response = $svc->create(100, "AUD")
            ->withCreditCard("58377235377aea03343240cc", "4111111111111111", "2020", "10", "Test Name", "123")
            ->withSchedule("month", 1)
            ->call();

        $svc = new Subscriptions();
        $response = $svc->get()
            ->withSubscriptionId($response["resource"]["data"]["_id"])
            ->call();
        
        $this->assertEquals("200", $response["status"]);
    }
    
    public function testGetByReference()
    {
        $custSvc = new Customers();
        $response = $custSvc->create("John", "Smith")
            ->withCreditCard("58377235377aea03343240cc", "4111111111111111", "2020", "10", "Test Name", "123")
            ->call();

        $customerId = $response["resource"]["data"]["_id"];

        $subscriptionSvc = new Subscriptions();
        $response = $subscriptionSvc->create(10, "AUD")
            ->withCustomerId($customerId)
            ->withSchedule("month", 1)
            ->call();

        $response = $subscriptionSvc->get()
            ->withParameters(["customer_id" => $customerId])
            ->call();
        
        $this->assertEquals("200", $response["status"]);
        $this->assertEquals(1, $response["resource"]["count"]);
    }
    
    public function testDelete()
    {
        $svc = new Subscriptions();
        $response = $svc->create(100, "AUD")
            ->withCreditCard("58377235377aea03343240cc", "4111111111111111", "2020", "10", "Test Name", "123")
            ->withSchedule("month", 1)
            ->call();

        $svc = new Subscriptions();
        $response = $svc->delete($response["resource"]["data"]["_id"])
            ->call();
        
        $this->assertEquals("200", $response["status"]);
    }
}
?>