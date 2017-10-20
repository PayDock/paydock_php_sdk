<?php
namespace Paydock\Sdk;

require_once(__DIR__."/../tools/ServiceHelper.php");
require_once(__DIR__."/../tools/JsonTools.php");

/*
 * This file is part of the Paydock.Sdk package.
 *
 * (c) Paydock
 *
 * For the full copyright and license information, please view
 * the LICENSE file which was distributed with this source code.
 */
final class Customers
{
    private $action;
    private $token;
    private $customerData;
    private $paymentSourceData;
    private $actionMap = array("create" => "POST");
    
    public function create($firstName = "", $lastName = "", $email = "", $phone = "", $refernce = "")
    {
        $this->action = "create";
        $this->customerData = ["first_name" => $firstName, "last_name" => $lastName, "email" => $email, "phone" => $phone, "refernce" => $refernce];
        return $this;
    }

    public function withToken($token)
    {
        $this->token = $token;
        return $this;
    }
    
    public function withCreditCard($gatewayId, $cardNumber, $expireYear, $expireMonth, $cardHolderName, $ccv)
    {
        $this->paymentSourceData = ["gateway_id" => $gatewayId, "card_number" => $cardNumber, "expire_month" => $expireMonth, "expire_year" => $expireYear, "card_name" => $cardHolderName, "card_ccv" => $ccv];
        return $this;
    }

    private function buildJson()
    {
        $arrayData = $this->customerData;

        if (!empty($this->token)) {
            $arrayData += ["token" => $this->token];
        } else if (!empty($this->paymentSourceData)) {
            $arrayData["payment_source"] = $this->paymentSourceData;
        }

        $jsonTools = new JsonTools();
        $arrayData = $jsonTools->CleanArray($arrayData);
        
        return json_encode($arrayData);
    }

    private function buildUrl()
    {
        return "customers";
    }

    public function call()
    {
        $data = $this->buildJson();
        $url = $this->buildUrl();

        return ServiceHelper::privateApiCall($this->actionMap[$this->action], $url, $data);
    }
}
?>