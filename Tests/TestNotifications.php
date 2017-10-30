<?php

require_once(__DIR__."/Shared/TestBase.php");
require_once(__DIR__."/Shared/ApiHelpers.php");
require_once(__DIR__."/../src/ResponseException.php");
require_once(__DIR__."/../src/services/Notifications.php");

use PHPUnit\Framework\TestCase;
use Paydock\Sdk\config;
use Paydock\Sdk\Notifications;
use Paydock\Sdk\ResponseException;

/**
 * @covers Notifications
 */
final class TestNotifications extends TestBase
{
    public function testCreateTemplate()
    {
        $svc = new Notifications();
        $response = $svc->createTemplate("Test body", "label", "transaction_success")
            ->call();
        
        $this->assertEquals("201", $response["status"]);
    }

    public function testUpdateTemplate()
    {
        $svc = new Notifications();
        $response = $svc->createTemplate("Test body", "label", "transaction_success")
            ->call();
        
        $response = $svc->updateTemplate($response["resource"]["data"]["_id"], "Test body_1", "labe_1", "transaction_success")
        ->call();

        $this->assertEquals("200", $response["status"]);
    }

    public function testGetTemplates()
    {
        $svc = new Notifications();
        $response = $svc->getTemplates()
            ->call();
        
        $this->assertEquals("200", $response["status"]);
    }
    
    public function testUDeleteTemplate()
    {
        $svc = new Notifications();
        $response = $svc->createTemplate("Test body", "label", "transaction_success")
            ->call();
        
        $response = $svc->deleteTemplate($response["resource"]["data"]["_id"])
            ->call();

        $this->assertEquals("200", $response["status"]);
    }
}
?>