<?php

require_once(__DIR__."/Shared/TestBase.php");
require_once(__DIR__."/Shared/ApiHelpers.php");

use PHPUnit\Framework\TestCase;
use Paydock\Sdk\Config;
use Paydock\Sdk\services\Tokens;
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
