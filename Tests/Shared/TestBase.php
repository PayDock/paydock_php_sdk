<?php

use PHPUnit\Framework\TestCase;
use Paydock\Sdk\Config;

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

