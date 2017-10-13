<?php
namespace Paydock\Sdk;

// TODO: replace all this with an autoloader
require_once(__DIR__."/../config.php");

use Paydock\Sdk\config;
/*
 * This file is part of the Paydock.Sdk package.
 *
 * (c) Paydock
 *
 * For the full copyright and license information, please view
 * the LICENSE file which was distributed with this source code.
 */
 final class ServiceHelper
{
    public static function publicApiCall($method, $endpoint, $data, $overridePublicKey = "")
    {
    }

    public static function privateApiCall($method, $endpoint, $data, $overrideSecretKey = "")
    {
        $config = new Config();
        $url = $config::baseUrl() + $endpoint;
        
        // handle overriding the secret key
        $secretKey = $config::$secretKey;
        if (!empty($overrideSecretKey)) {
            $secretKey = $overrideSecretKey;
        }

        // TODO: add headers

        // TODO: make the call

        // TODO: catch any errors

        // TODO: force to TLS 1.2
    }
}