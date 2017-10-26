<?php

require_once(__DIR__."/Shared/TestBase.php");
require_once(__DIR__."/../src/ResponseException.php");
require_once(__DIR__."/../src/services/Tokens.php");

use PHPUnit\Framework\TestCase;
use Paydock\Sdk\config;
use Paydock\Sdk\tokens;
use Paydock\Sdk\ResponseException;

/**
 * @covers tokens
 */
final class TestCustomers extends TestBase
{
    public function testCreateTokenWithCard()
    {
        $svc = new Tokens();
        $response = $svc->create("John", "Smith")
            ->withCreditCard(self::creditGateway, "4111111111111111", "2020", "10", "Test Name", "123")
            ->call();
        
        $this->assertEquals("201", $response["status"]);
    }
}
?>