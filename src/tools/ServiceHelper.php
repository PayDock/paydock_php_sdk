<?php
namespace Paydock\Sdk\tools;

use Paydock\Sdk\Config;
use Paydock\Sdk\ResponseException;
use Paydock\Sdk\tools\JWTTools;

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
    public static function publicApiCall($method, $endpoint, $data)
    {
        $config = new Config();
        $url = $config::baseUrl() . $endpoint;

        return ServiceHelper::ApiCall($data, $url, $method);
    }

    public static function privateApiCall($method, $endpoint, $data, $overrideSecretKeyOrAccessToken = "")
    {
        $config = new Config();
        $url = $config::baseUrl() . $endpoint;

        // handle overriding the secret key or access token
        $secretKey = $config::$secretKey;
        $accessToken = $config::$accessToken;

        if (!empty($overrideSecretKeyOrAccessToken)) {
            if ((new JWTTools)->isJWTToken($overrideSecretKeyOrAccessToken)) {
                $accessToken = $overrideSecretKeyOrAccessToken;
            } else {
                $secretKey = $overrideSecretKeyOrAccessToken;
            }
        }

        return ServiceHelper::ApiCall($data, $url, $method, $secretKey, $accessToken);
    }

     private static function ApiCall($data, $url, $method, $secretKey = "", $accessToken = "")
     {
         $ch = curl_init();
         curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
         curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
         curl_setopt($ch, CURLOPT_URL, $url);
         curl_setopt($ch, CURLOPT_ENCODING, "gzip");
         curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
         curl_setopt($ch, CURLOPT_SSLVERSION, CURL_SSLVERSION_TLSv1_2);
         curl_setopt($ch, CURLOPT_TIMEOUT_MS, Config::$timeoutMilliseconds);

         $headers = [
             "Content-Type: application/json",
             "Content-Length: ". strlen($data)
         ];

         if (!empty($secretKey)) {
             $headers[] = "x-user-secret-key: $secretKey";
         }
         if (!empty($accessToken)) {
             $headers[] = "x-access-token: $accessToken";
         }

         curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

         $response = curl_exec($ch);
         $curlInfo = curl_getinfo($ch);
         $curlError = curl_errno($ch);
         curl_close($ch);

         // error in response
         if ($response === false || ($curlInfo['http_code'] != 200 && $curlInfo['http_code'] != 201)) {
             ServiceHelper::BuildExceptionResponse($curlInfo, $response, $curlError);
         }

         return json_decode($response, true);
     }

     /**
      * @throws ResponseException
      */
     private static function BuildExceptionResponse($curlInfo, $response, $curlError)
     {
         $ex = new ResponseException();
         if ($curlError === 28) {
             $ex->setCode("400");
             $ex->setMessage("Request Timeout");
         } else {
             $ex->setCode($curlInfo['http_code']);
             if ($response != false) {
                 $ex->setJsonResponse($response);
                 $parsedResponse = json_decode($response, true);

                 if (!empty($parsedResponse["error"]["message"]["message"])) {
                     $ex->setMessage($parsedResponse["error"]["message"]["message"]);
                 } else if (!empty($parsedResponse["error"]["message"])) {
                     $ex->setMessage($parsedResponse["error"]["message"]);
                 }
             }
         }

         $ex->setMessage($ex->getCode() . " - " . $ex->getMessage());

         throw $ex;
     }
 }
