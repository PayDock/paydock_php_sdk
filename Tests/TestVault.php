<?php

require_once(__DIR__."/Shared/TestBase.php");
require_once(__DIR__."/Shared/ApiHelpers.php");
require_once(__DIR__."/../src/ResponseException.php");
require_once(__DIR__."/../src/services/Vault.php");

use PHPUnit\Framework\TestCase;
use Paydock\Sdk\config;
use Paydock\Sdk\Vault;
use Paydock\Sdk\ResponseException;

/**
 * @covers charges
 */
final class TestCharges extends TestBase
{
    public function testCreate()
    {
        $svc = new Vault();
        $response = $svc->create("4111111111111111", "2020", "10", "Test Name")
            ->call();
        
        $this->assertEquals("201", $response["status"]);
    }

    public function testGet()
    {
        $svc = new Vault();
        $response = $svc->create("4111111111111111", "2020", "10", "Test Name")
            ->call();
        
        $response = $svc->get()
            ->call();

        $this->assertEquals("200", $response["status"]);
    }
    
    public function testGetById()
    {
        $svc = new Vault();
        $response = $svc->create("4111111111111111", "2020", "10", "Test Name")
            ->call();
        
        $vaultToken = $response["resource"]["data"]["vault_token"];
        
        $response = $svc->get()
            ->withToken($vaultToken)
            ->call();

        $this->assertEquals("200", $response["status"]);
    }
}
?>