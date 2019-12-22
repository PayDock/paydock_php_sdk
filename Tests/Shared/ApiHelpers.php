<?php

require_once(__DIR__ . "/../../src/Config.php");
require_once(__DIR__ . "/../../src/services/Charges.php");
require_once(__DIR__."/../../src/services/Customers.php");
require_once(__DIR__."/../../src/services/Tokens.php");
require_once(__DIR__."/../../src/services/Notifications.php");

use Paydock\Sdk\config;
use Paydock\Sdk\charges;
use Paydock\Sdk\Customers;
use Paydock\Sdk\Tokens;
use Paydock\Sdk\Notifications;

/**
 * @covers TestBase
 */
class ApiHelpers
{
    public static function createCharge($gatewayId)
    {
        $svc = new Charges();
        return $svc->create(100, "AUD")
            ->withCreditCard($gatewayId, "4111111111111111", "2020", "10", "Test Name", "123")
            ->call();
    }

    public static function createToken($gatewayId)
    {
        $svc = new Tokens();
        return $svc->create("John", "Smith")
            ->withCreditCard($gatewayId, "4111111111111111", "2020", "10", "Test Name", "123")
            ->call();
    }
    
    public static function createCustomer($gatewayId)
    {
        $svc = new Customers();
        return $svc->create("John", "Smith")
            ->withCreditCard($gatewayId, "4111111111111111", "2020", "10", "Test Name", "123")
            ->call();
    }

    public static function createNotificationTemplate()
    {
        $svc = new Notifications();
        return $svc->createTemplate("Test body", "label", "refund_failure")
            ->call();
    }

    public static function createNotificationTrigger($templateId)
    {
        $svc = new Notifications();
        return $svc->addTrigger("webhook", "https://www.paydock.com", $templateId, "refund_failure")
            ->call();
    }
}
?>