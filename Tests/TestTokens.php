<?php

require_once(__DIR__."/Shared/TestBase.php");
require_once(__DIR__."/Shared/ApiHelpers.php");
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
        $response = ApiHelpers::createToken(self::creditGateway);
        
        $this->assertEquals("201", $response["status"]);
    }
}
?>