<?php

use Paydock\Sdk\Config;
use Paydock\Sdk\services\Charges;
use Paydock\Sdk\services\Customers;
use Paydock\Sdk\services\Tokens;
use Paydock\Sdk\services\Notifications;

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

