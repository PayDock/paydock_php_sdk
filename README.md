# Welcome to Paydock php SDK

This SDK provides a wrapper around the PayDock REST API.

For more info on the Paydock API, see our [full documentation](https://docs.paydock.com).

# Getting the SDK

The SDK is available through [composer](https://packagist.org/packages/paydock/paydock_php_sdk), or pull it down from [github](https://github.com/PayDockDev/paydock_php_sdk) directly.

# Simple example to create a single charge


``` php

Config::initialise("sandbox", "secret_key", "public_key");

$svc = new Charges();
$response = $svc->create(100, "AUD")
    ->withCreditCard("58377235377aea03343240cc", "4111111111111111", "2020", "10", "Test Name", "123")
    ->call();

```