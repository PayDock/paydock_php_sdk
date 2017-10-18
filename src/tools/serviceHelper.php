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

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_URL, Config::baseUrl() . $endpoint);
        curl_setopt($ch, CURLOPT_ENCODING, "gzip");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Content-Type: application/json",
            "Content-Length: ". strlen($data),
            "x-user-token:". $secretKey,
        ]);
        
        $response = curl_exec($ch);
        curl_close($ch);
        return json_decode($response, true);
        
        // TODO: implement timeout

        // TODO: catch any errors, return status code

        // TODO: force to TLS 1.2
    }
}