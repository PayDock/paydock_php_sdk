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
    public function testcreateNotificationTemplate()
    {
        $response = ApiHelpers::createNotificationTemplate();
        
        $this->assertEquals("201", $response["status"]);
    }

    public function testUpdateTemplate()
    {
        $response = ApiHelpers::createNotificationTemplate();

        $svc = new Notifications();
        $response = $svc->updateTemplate($response["resource"]["data"]["_id"], "Test body_1", "labe_1", "refund_failure")
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
    
    public function testDeleteTemplate()
    {
        $response = ApiHelpers::createNotificationTemplate();

        $svc = new Notifications();        
        $response = $svc->deleteTemplate($response["resource"]["data"]["_id"])
            ->call();

        $this->assertEquals("200", $response["status"]);
    }
    
    public function testAddTrigger()
    {
        $response = ApiHelpers::createNotificationTemplate();

        $response = ApiHelpers::createNotificationTrigger($response["resource"]["data"]["_id"]);

        $this->assertEquals("201", $response["status"]);
    }
    
    public function testGetTriggers()
    {
        $svc = new Notifications();        
        $response = $svc->getTriggers()
            ->call();

        $this->assertEquals("200", $response["status"]);
    }
    
    public function testGetTrigger()
    {
        $svc = new Notifications();
        $response = ApiHelpers::createNotificationTemplate();
        
        $response = ApiHelpers::createNotificationTrigger($response["resource"]["data"]["_id"]);
        
        $response = $svc->getTrigger($response["resource"]["data"]["_id"])
            ->call();

        $this->assertEquals("200", $response["status"]);
    }
    
    public function testDeleteTrigger()
    {
        $svc = new Notifications();
        $response = ApiHelpers::createNotificationTemplate();
        
        $response = ApiHelpers::createNotificationTrigger($response["resource"]["data"]["_id"]);
        
        $response = $svc->deleteTrigger($response["resource"]["data"]["_id"])
            ->call();

        $this->assertEquals("200", $response["status"]);
    }
    
    public function testGetlog()
    {
        $svc = new Notifications();
        $response = $svc->getLog(["success" => "true"])
            ->call();

        $this->assertEquals("200", $response["status"]);
    }
    
    public function testArchivelog()
    {
        $svc = new Notifications();

        $this->markTestSkipped("disabled this test as it needs a notification to have been sent");
        return;

        $response = $svc->archiveLog("<id goes here>")
            ->call();

        $this->assertEquals("200", $response["status"]);
    }
}
?>