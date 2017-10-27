<?php

require_once(__DIR__."/../../src/config.php");

use PHPUnit\Framework\TestCase;
use Paydock\Sdk\config;

/**
 * @covers TestBase
 */
class TestBase extends TestCase
{
    const creditGateway = "<credit card gateway id here>";
    const bsbGateway = "<bank account gateway id here>";

    protected function setUp()
    {
        Config::initialise("sandbox", "<private key here>", "<public key here>");
    }
}
?>