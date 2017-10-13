<?php

require_once(__DIR__."/../src/config.php");
require_once(__DIR__."/../src/services/charges.php");

use PHPUnit\Framework\TestCase;
use Paydock\Sdk\config;
use Paydock\Sdk\charges;

// TODO: follow PSR-0 & PSR-4 for autoloading

/**
 * @covers charges
 */
final class TestCharges extends TestCase
{
    protected function setUp()
    {
        Config::initialise("sandbox", "fccbf57c8a65a609ed86edd417177905bfd5a99b", "cc5bedb53a1b64491b5b468a2486b32cc250cda2");
    }

    public function testcreateCharge()
    {

        $svc = new Charges();
        $response = $svc->create(100, "AUD")
            ->withToken("tokenId")
            ->call();
        
        // TODO: perform some validation of the test
    }
}
?>