<?php

require_once(__DIR__."/../../src/config.php");

use PHPUnit\Framework\TestCase;
use Paydock\Sdk\config;

/**
 * @covers TestBase
 */
class TestBase extends TestCase
{
    const creditGateway = "58377235377aea03343240cc";
    const bsbGateway = "58814949ca63b81cbd2acad0";

    protected function setUp()
    {
        Config::initialise("sandbox", "fccbf57c8a65a609ed86edd417177905bfd5a99b", "cc5bedb53a1b64491b5b468a2486b32cc250cda2");
    }
}
?>