<?php

require_once(__DIR__."/Shared/TestBase.php");
require_once(__DIR__."/Shared/ApiHelpers.php");
require_once(__DIR__."/../src/ResponseException.php");
require_once(__DIR__."/../src/services/Charges.php");
require_once(__DIR__."/../src/services/Customers.php");
require_once(__DIR__."/../src/services/Tokens.php");

use PHPUnit\Framework\TestCase;
use Paydock\Sdk\config;
use Paydock\Sdk\charges;
use Paydock\Sdk\Customers;
use Paydock\Sdk\Tokens;
use Paydock\Sdk\ResponseException;

/**
 * @covers charges
 */
final class TestCharges extends TestBase
{
    public function testCreateChargeWithCard()
    {
        $response = ApiHelpers::createCharge(self::creditGateway);
        
        $this->assertEquals("201", $response["status"]);
    }
    
    public function testCreateChargeWithBankAccount()
    {
        $svc = new Charges();
        $response = $svc->create(100, "AUD")
            ->withBankAccount(self::bsbGateway, "test", "012003", "456456")
            ->includeCustomerDetails("John", "Smith", "test@email.com", "+61414111111")
            ->call();
        
        $this->assertEquals("201", $response["status"]);
    }
    
    public function testCreateChargeWithoutGateway()
    {
        $svc = new Charges();
        
        $this->expectException(ResponseException::class);

        $response = $svc->create(100, "AUD")
            ->withCreditCard("", "4111111111111111", "2020", "10", "Test Name", "123")
            ->call();
    }
    
    public function testCreateChargeWithLowTimeout()
    {
        Config::$timeoutMilliseconds = 10;

        try
        {
            ApiHelpers::createCharge(self::creditGateway);
        } catch (ResponseException $ex) {
            $this->assertEquals("400", $ex->Status);
        }

        Config::$timeoutMilliseconds = 60000;
    }

    public function testCreateChargeWithoutPaymentDetails()
    {
        $svc = new Charges();
        
        $exceptionOccurred = false;
        try {
            $response = $svc->create(100, "AUD")
                ->call();
        } catch (BadMethodCallException $ex) {
            $exceptionOccurred = true;
        }

        $this->assertTrue($exceptionOccurred);
    }

    public function testGet()
    {
        $svc = new Charges();

        $response = $svc->get()
            ->call();

        $this->assertEquals("200", $response["status"]);
    }

    public function testGetbyId()
    {
        $svc = new Charges();

        $response = ApiHelpers::createCharge(self::creditGateway);

        $response = $svc->get()
            ->withChargeId($response["resource"]["data"]["_id"])
            ->call();

        $this->assertEquals("200", $response["status"]);
    }

    public function testGetByReference()
    {
        $svc = new Charges();

        $reference = uniqid();
        $response = $svc->create(10, "AUD", "", $reference)
            ->withCreditCard(self::creditGateway, "4111111111111111", "2021", "10", "Test Name", "123")
            ->call();

        $response = $svc->get()
            ->withParameters(["reference" => $reference])
            ->call();

        $this->assertEquals("200", $response["status"]);
        $this->assertEquals(1, $response["resource"]["count"]);
    }

    public function testRefund()
    {
        $svc = new Charges();
        $response = ApiHelpers::createCharge(self::creditGateway);

        $chargeId = $response["resource"]["data"]["_id"];
        
        $this->markTestSkipped("this test fails due to limitations at the gateway level");
        return;

        $response = $svc->refund($chargeId)
            ->call();

        $this->assertEquals("200", $response["status"]);
    }
    
    public function testCreateWithCustomerId()
    {
        $response = ApiHelpers::createCustomer(self::creditGateway);

        $customerId = $response["resource"]["data"]["_id"];

        $chargeSvc = new Charges();
        $response = $chargeSvc->create(10, "AUD")
            ->withCustomerId($customerId)
            ->call();

        $this->assertEquals("201", $response["status"]);
    }

    public function testArchive()
    {
        $svc = new Charges();
        $response = ApiHelpers::createCharge(self::creditGateway);

        $chargeId = $response["resource"]["data"]["_id"];

        $response = $svc->archive($chargeId)
            ->call();

        $this->assertEquals("200", $response["status"]);
    }
    
    public function testCreateWithToken()
    {
        $response = ApiHelpers::createToken(self::creditGateway);

        $tokenId = $response["resource"]["data"];
        
        $chargeSvc = new Charges();
        $response = $chargeSvc->create(10, "AUD")
            ->withToken($tokenId)
            ->call();

        $this->assertEquals("201", $response["status"]);
    }

    public function testChargeWithTransfer()
    {
        $transfer = [
            ["amount" => 60, "currency" => "AUD", "destination" => "stripe_account_1"],
            ["amount" => 20, "currency" => "AUD", "destination" => "stripe_account_2"]
        ];
        $svc = new Charges();
        $response = $svc->create(100, "AUD")
            ->withCreditCard(self::creditGateway, "4111111111111111", "2020", "10", "Test Name", "123")
            ->includeTransfer("stripe_group_id", $transfer)
            ->call();
        
        $this->assertEquals("201", $response["status"]);
    }

    public function testAuthoriseThenCapture()
    {
        $chargeSvc = new Charges();
        $response = $chargeSvc->create(10, "AUD", "test_charge", "reference", false)
            ->withCreditCard(self::authorizeGateway, "4111111111111111", "2020", "10", "Test Name", "123")
            ->call();

        $chargeId = $response["resource"]["data"]["_id"];

        $response = $chargeSvc->capture($chargeId)
            ->call();

        $this->assertEquals("201", $response["status"]);
    }
}
?>