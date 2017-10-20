<?php

require_once(__DIR__."/../src/config.php");
require_once(__DIR__."/../src/ResponseException.php");
require_once(__DIR__."/../src/services/Customers.php");

use PHPUnit\Framework\TestCase;
use Paydock\Sdk\config;
use Paydock\Sdk\customers;
use Paydock\Sdk\ResponseException;

/**
 * @covers charges
 */
final class TestCustomers extends TestCase
{
    protected function setUp()
    {
        Config::initialise("sandbox", "fccbf57c8a65a609ed86edd417177905bfd5a99b", "cc5bedb53a1b64491b5b468a2486b32cc250cda2");
    }

    public function testCreateCustomerWithToken()
    {
        $this->markTestIncomplete("not implemented yet");
    }

    public function testCreateCustomerWithCard()
    {
        $svc = new Customers();
        $response = $svc->create("John", "Smith")
            ->withCreditCard("58377235377aea03343240cc", "4111111111111111", "2020", "10", "Test Name", "123")
            ->call();
        
        $this->assertEquals("201", $response["status"]);
    }
    
    public function testCreateCustomerWithBankAccount()
    {
        $svc = new Customers();
        $response = $svc->create("John", "Smith", "test@test.com", "+61414111111")
            ->withBankAccount("58814949ca63b81cbd2acad0", "test", "012003", "456456")
            ->includeAddress("1 something st", "", "NSW", "Australia", "Sydney", "2000")
            ->includeMeta("")
            ->call();
        
        $this->assertEquals("201", $response["status"]);
    }
}
?>