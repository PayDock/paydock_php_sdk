<?php
namespace Paydock\Sdk;

use Paydock\Sdk\tools\JWTTools;

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
    public static $accessToken;
    public static $url;
    public static $timeoutMilliseconds;

    public static function initialise($environmentOrUrl, $secretKeyOrAccessToken, $publicKey, $timeoutMilliseconds = 60000)
    {

        // force lower case url
        $environmentOrUrl = strtolower($environmentOrUrl);

        // set environment and url
        if ($environmentOrUrl == "sandbox") {
            self::$url = "https://api-sandbox.paydock.com/v1/";
            self::$environment = "sandbox";
        } else if ($environmentOrUrl == "production") {
            self::$url = "https://api.paydock.com/v1/";
            self::$environment = "production";
        } else {
            self::$url = rtrim($environmentOrUrl,"/") . "/";
            self::$environment = "other";
        }

        //test secret key or access token
        if ((new JWTTools)->isJWTToken($secretKeyOrAccessToken)) {
            self::$secretKey = null;
            self::$accessToken = $secretKeyOrAccessToken;
        } else {
            self::$secretKey = $secretKeyOrAccessToken;
            self::$accessToken = null;
        }

        self::$timeoutMilliseconds = $timeoutMilliseconds;
    }

    public static function baseUrl()
    {
        return self::$url;
    }
}
