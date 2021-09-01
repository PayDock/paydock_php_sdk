<?php

require_once(__DIR__."/Shared/TestBase.php");
require_once(__DIR__."/Shared/ApiHelpers.php");

use PHPUnit\Framework\TestCase;
use Paydock\Sdk\Config;
use Paydock\Sdk\services\Gateways;
use Paydock\Sdk\ResponseException;

/**
 * @covers Gateways
 */
final class TestGateways extends TestBase
{
    public function testCreateGateway()
    {
        $svc = new Gateways();
        $response = $svc->create("Brain", "BraintreeTesting", "n8nktcb42fy8ttgt", "c865e194d750148b93284c0c026e5f2a", "r7pcwvkbkgjfzk99", "test")
            ->call();

        $this->assertEquals("201", $response["status"]);

        // clean up
        $response = $svc->delete($response["resource"]["data"]["_id"])
            ->call();
    }

    public function testGetGateway()
    {
        $svc = new Gateways();
        $response = $svc->create("Brain", "BraintreeTesting", "n8nktcb42fy8ttgt", "c865e194d750148b93284c0c026e5f2a", "r7pcwvkbkgjfzk99", "test")
            ->call();

        $gatewayId = $response["resource"]["data"]["_id"];
        $response = $svc->get()
            ->call();

        $this->assertEquals("200", $response["status"]);

        $response = $svc->get()
            ->withId($gatewayId)
            ->call();

        $this->assertEquals("200", $response["status"]);

        // clean up
        $response = $svc->delete($gatewayId)
            ->call();
    }

    public function testUpdateGateway()
    {
        $svc = new Gateways();
        $response = $svc->create("Brain", "BraintreeTesting", "n8nktcb42fy8ttgt", "c865e194d750148b93284c0c026e5f2a", "r7pcwvkbkgjfzk99", "test")
            ->call();

        $gatewayId = $response["resource"]["data"]["_id"];

        $response = $svc->update($gatewayId, "Brain", "BraintreeTesting2", "n8nktcb42fy8ttgt", "c865e194d750148b93284c0c026e5f2a", "r7pcwvkbkgjfzk99", "test")
            ->call();

        $this->assertEquals("200", $response["status"]);

        // clean up
        $response = $svc->delete($gatewayId)
            ->call();
    }
}

