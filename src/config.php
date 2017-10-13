<?php
namespace Paydock\Sdk;

/*
 * This file is part of the Paydock.Sdk package.
 *
 * (c) Paydock
 *
 * For the full copyright and license information, please view
 * the LICENSE file which was distributed with this source code.
 */
class Config
{    
    public static $environment;
    public static $secretKey;
    public static $publicKey;
    public static $timeoutMilliseconds;

    public static function initialise($environment, $secretKey, $publicKey, $timeoutMilliseconds = 60)
    {
        // TODO: validate the environment
        self::$environment = $environment;
        self::$secretKey = $secretKey;
        self::$publicKey = $publicKey;
        self::$timeoutMilliseconds = $timeoutMilliseconds;
    }

    public static function baseUrl()
    {
        if (self::$environment == "sandbox") {
            return "https://api-sandbox.paydock.com/v1/";
        } else {
            return "https://api.paydock.com/v1/";            
        }
    }
}
?>