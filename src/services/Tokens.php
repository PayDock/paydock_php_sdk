<?php
namespace Paydock\Sdk;

require_once(__DIR__."/../tools/ServiceHelper.php");
require_once(__DIR__."/../tools/JsonTools.php");
require_once(__DIR__."/../tools/UrlTools.php");

/*
 * This file is part of the Paydock.Sdk package.
 *
 * (c) Paydock
 *
 * For the full copyright and license information, please view
 * the LICENSE file which was distributed with this source code.
 */
final class Tokens
{
    private $actionMap = array("create" => "POST");
    private $paymentSourceData = array();
    
    public function create()
    {
        $this->action = "create";
        return $this;
    }

    public function withCreditCard($gatewayId, $cardNumber, $expireYear, $expireMonth, $cardHolderName, $ccv)
    {
        $this->paymentSourceData = ["gateway_id" => $gatewayId, "card_number" => $cardNumber, "expire_month" => $expireMonth, "expire_year" => $expireYear, "card_name" => $cardHolderName, "card_ccv" => $ccv];
        return $this;
    }
    
    public function withBankAccount($gatewayId, $accountName, $accountBsb, $accountNumber, $accountHolderType = "", $accountBankName = "")
    {
        $this->paymentSourceData = ["gateway_id" => $gatewayId, "type" => "bank_account", "account_name" => $accountName, "account_bsb" => $accountBsb, "account_number" => $accountNumber, "account_holder_type" => $accountHolderType, "account_bank_name" => $accountBankName, "type" => "bsb"];
        return $this;
    }

    public function includeCustomerDetails($firstName, $lastName, $email, $phone)
    {
        $this->paymentSourceData += ["first_name" => $firstName, "last_name" => $lastName, "email" => $email, "phone" => $phone];
        return $this;
    }

    public function includeAddress($addressLine1, $addressLine2, $addressState, $addressCountry, $addressCity, $addressPostcode)
    {
        $this->paymentSourceData += ["address_line1" => $addressLine1, "address_line2" => $addressLine2, "address_state" => $addressState, "address_country" => $addressCountry, "address_city" => $addressCity, "address_postcode" => $addressPostcode];
        return $this;
    }

    private function buildJson()
    {
        $jsonTools = new JsonTools();

        $arrayData = $jsonTools->CleanArray($this->paymentSourceData);

        switch ($this->action) {
            case "create":
                return json_encode($arrayData);
        }

        return "";
    }

    private function buildUrl()
    {
        $config = new Config();
        $urlTools = new UrlTools();
        
        return "payment_sources/tokens?public_key=" . urlencode($config::$publicKey);
    }

    public function call()
    {
        $data = $this->buildJson();
        $url = $this->buildUrl();

        return ServiceHelper::privateApiCall($this->actionMap[$this->action], $url, $data);
    }
}
?>