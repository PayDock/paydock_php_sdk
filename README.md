# Deprecation Notice: paydock_java_sdk is no longer supported or maintained.

We are deprecating the paydock_ios_sdk repository in favor of newer, better-maintained alternatives. This means that the code in this repository will not receive any updates, bug fixes, or security patches.

Please do not use this SDK for new development, as it will lead to compatibility issues and potential security risks. Instead, we recommend using direct REST API integration.

We apologize for any inconvenience this may cause, but we must prioritize the stability and security of our software.

If you have any questions or concerns, please open an issue in the repository or contact Paydock Support.

Thank you for your understanding.


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
