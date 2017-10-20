<?php

require_once(__DIR__."/../src/config.php");
require_once(__DIR__."/../src/ResponseException.php");
require_once(__DIR__."/../src/services/charges.php");

use PHPUnit\Framework\TestCase;
use Paydock\Sdk\config;
use Paydock\Sdk\charges;
use Paydock\Sdk\ResponseException;

/**
 * @covers charges
 */
final class TestCharges extends TestCase
{
    protected function setUp()
    {
        Config::initialise("sandbox", "fccbf57c8a65a609ed86edd417177905bfd5a99b", "cc5bedb53a1b64491b5b468a2486b32cc250cda2");
    }

    public function testCreateChargeWithCard()
    {
        $svc = new Charges();
        $response = $svc->create(100, "AUD")
            ->withCreditCard("58377235377aea03343240cc", "4111111111111111", "2020", "10", "Test Name", "123")
            ->call();
        
        $this->assertEquals("201", $response["status"]);
    }
    
    public function testCreateChargeWithBankAccount()
    {
        $svc = new Charges();
        $response = $svc->create(100, "AUD")
            ->withBankAccount("58814949ca63b81cbd2acad0", "test", "012003", "456456")
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
        $svc = new Charges();

        try
        {
            $response = $svc->create(100, "AUD")
                ->withCreditCard("58377235377aea03343240cc", "4111111111111111", "2020", "10", "Test Name", "123")
                ->call();
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

        $response = $svc->create(10, "AUD")
            ->withCreditCard("58377235377aea03343240cc", "4111111111111111", "2021", "10", "Test Name", "123")
            ->call();

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
            ->withCreditCard("58377235377aea03343240cc", "4111111111111111", "2021", "10", "Test Name", "123")
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
        $response = $svc->create(10, "AUD")
            ->withBankAccount("58814949ca63b81cbd2acad0", "test", "012003", "456456")
            ->includeCustomerDetails("John", "Smith", "test@email.com", "+61414111111")
            ->call();

        $chargeId = $response["resource"]["data"]["_id"];
        
        $this->markTestSkipped("this test fails due to limitations at the gateway level");
        return;

        $response = $svc->refund($chargeId)
            ->call();

        $this->assertEquals("200", $response["status"]);
    }
    
    public function testCreateWithCustomerId()
    {
        $this->markTestIncomplete("not implemented yet");
    }

    public function testCreateWithToken()
    {
        $this->markTestIncomplete("not implemented yet");
    }

    public function testArchive()
    {
        $svc = new Charges();
        $response = $svc->create(10, "AUD")
            ->withCreditCard("58377235377aea03343240cc", "4111111111111111", "2021", "10", "Test Name", "123")
            ->call();

        $chargeId = $response["resource"]["data"]["_id"];

        $response = $svc->archive($chargeId)
            ->call();

        $this->assertEquals("200", $response["status"]);
    }
}
?>