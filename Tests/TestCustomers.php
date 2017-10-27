<?php

require_once(__DIR__."/Shared/TestBase.php");
require_once(__DIR__."/Shared/ApiHelpers.php");
require_once(__DIR__."/../src/ResponseException.php");
require_once(__DIR__."/../src/services/Customers.php");
require_once(__DIR__."/../src/services/Tokens.php");

use PHPUnit\Framework\TestCase;
use Paydock\Sdk\config;
use Paydock\Sdk\Customers;
use Paydock\Sdk\Tokens;
use Paydock\Sdk\ResponseException;

/**
 * @covers Customers
 */
final class TestCustomers extends TestBase
{
    public function testCreateCustomerWithToken()
    {
        $response = ApiHelpers::createToken(self::creditGateway);

        $tokenId = $response["resource"]["data"];

        $customerSvc = new Customers();
        $response = $customerSvc->create("John", "Smith")
            ->withToken($tokenId)
            ->call();

        $this->assertEquals("201", $response["status"]);
    }

    public function testCreateCustomerWithCard()
    {
        $response = ApiHelpers::createCustomer(self::creditGateway);
        
        $this->assertEquals("201", $response["status"]);
    }
    
    public function testCreateCustomerWithBankAccount()
    {
        $svc = new Customers();
        $response = $svc->create("John", "Smith", "test@test.com", "+61414111111")
            ->withBankAccount(self::bsbGateway, "test", "012003", "456456")
            ->includeAddress("1 something st", "", "NSW", "Australia", "Sydney", "2000")
            ->includeMeta("")
            ->call();
        
        $this->assertEquals("201", $response["status"]);
    }
    
    public function testGetCustomers()
    {
        $svc = new Customers();
        $response = $svc->get()
            ->call();
        
        $this->assertEquals("200", $response["status"]);
    }
    
    public function testGetbyId()
    {
        $response = ApiHelpers::createCustomer(self::creditGateway);

        $svc = new Customers();
        $response = $svc->get()
            ->withCustomerId($response["resource"]["data"]["_id"])
            ->call();

        $this->assertEquals("200", $response["status"]);
    }

    public function testGetByReference()
    {
        $svc = new Customers();

        $reference = uniqid();
        $response = $svc->create("John", "Smith", "test@test.com", "+61414958111", $reference)
            ->withCreditCard(self::creditGateway, "4111111111111111", "2020", "10", "Test Name", "123")
            ->call();

        $response = $svc->get()
            ->withParameters(["reference" => $reference])
            ->call();

        $this->assertEquals("200", $response["status"]);
        $this->assertEquals(1, $response["resource"]["count"]);
    }
    
    public function testGetPaymentSources()
    {
        $svc = new Customers();

        $reference = uniqid();
        $response = $svc->create("John", "Smith", "test@test.com", "+61414958111", $reference)
            ->withCreditCard(self::creditGateway, "4111111111111111", "2020", "10", "Test Name", "123")
            ->call();

        $response = $svc->get()
             ->withParameters(["id" => $response["resource"]["data"]["_id"]])
            ->call();

        $queryToken = $response["resource"]["query_token"];

        $response = $svc->getPaymentSources($queryToken)
            ->call();

        $this->assertEquals("200", $response["status"]);
    }
    
    public function testUpdateCustomer()
    {
        $response = ApiHelpers::createCustomer(self::creditGateway);

        $svc = new Customers();
        $response = $svc->update($response["resource"]["data"]["_id"], "John1", "Smith1", "test@test1.com", "+61414958111")
            ->call();

        $this->assertEquals("200", $response["status"]);
        $this->assertEquals("John1", $response["resource"]["data"]["first_name"]);
        $this->assertEquals("Smith1", $response["resource"]["data"]["last_name"]);
        $this->assertEquals("test@test1.com", $response["resource"]["data"]["email"]);
    }
    
    public function testArchive()
    {
        $response = ApiHelpers::createCustomer(self::creditGateway);

        $svc = new Customers();
        $response = $svc->archive($response["resource"]["data"]["_id"])
            ->call();

        $this->assertEquals("200", $response["status"]);
    }
}
?>