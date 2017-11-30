<?php

require_once(__DIR__."/../../src/config.php");

use PHPUnit\Framework\TestCase;
use Paydock\Sdk\config;

/**
 * @covers TestBase
 */
class TestBase extends TestCase
{
    const creditGateway = "";
    const authorizeGateway = "";
    const bsbGateway = "";

    protected function setUp()
    {
        Config::initialise("sandbox", "", "");
    }
}
?>