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
final class Charges
{
    private $chargeData;
    private $token;
    private $customerId;
    private $paymentSourceData = array();
    private $customerData = array();
    private $action;
    private $meta;
    private $chargeId;
    private $chargeFilter;
    private $refundAmount;
    private $actionMap = array("create" => "POST", "get" => "GET", "refund" => "POST", "archive" => "DELETE");
    
    public function create($amount, $currency, $description = "", $reference = "")
    {
        $this->chargeData = ["amount" => $amount, "currency"=>$currency, "description"=>$description, "reference" => $reference];
        $this->action = "create";
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
    
    public function withBankAccount($gatewayId, $accountName, $accountBsb, $accountNumber, $accountHolderType = "", $accountBankName = "")
    {
        $this->paymentSourceData = ["gateway_id" => $gatewayId, "type" => "bank_account", "account_name" => $accountName, "account_bsb" => $accountBsb, "account_number" => $accountNumber, "account_holder_type" => $accountHolderType, "account_bank_name" => $accountBankName, "type" => "bsb"];
        return $this;
    }

    public function withCustomerId($customerId, $paymentSourceId = "")
    {
        $this->customerId = $customerId;
        if (!empty($paymentSourceId)) {
            $this->customerData["payment_source_id"] = $paymentSourceId;
        }
        return $this;
    }

    public function includeCustomerDetails($firstName, $lastName, $email, $phone)
    {
        $this->customerData += ["first_name" => $firstName, "last_name" => $lastName, "email" => $email, "phone" => $phone];
        return $this;
    }

    public function includeAddress($addressLine1, $addressLine2, $addressState, $addressCountry, $addressCity, $addressPostcode)
    {
        $this->paymentSourceData += ["address_line1" => $addressLine1, "address_line2" => $addressLine2, "address_state" => $addressState, "address_country" => $addressCountry, "address_city" => $addressCity, "address_postcode" => $addressPostcode];
        return $this;
    }

    public function includeMeta($meta)
    {
        $this->meta = $meta;
        return $this;
    }

    private function buildCreateJson()
    {
        if (empty($this->token) && empty($this->customerId) && count($this->paymentSourceData) == 0) {
            throw new \BadMethodCallException("must call with a token, customer or payment information");
        }

        $arrayData = [
            'amount'      => $this->chargeData["amount"],
            'currency'    => $this->chargeData["currency"],
            'reference'   => $this->chargeData["reference"],
            'description'   => $this->chargeData["description"]
        ];

        if (!empty($this->token)) {
            $arrayData += ["token" => $this->token];
        } else if (!empty($this->customerId)) {
            $arrayData += ["customer_id" => $this->customerId];
        }
    
        if (!empty($this->customerData)) {
            $arrayData += ["customer" => $this->customerData];
        }

        if (!empty($this->paymentSourceData)) {
            if (empty($arrayData["customer"])) {
                $arrayData["customer"] = array();
            }
            $arrayData["customer"]["payment_source"] = $this->paymentSourceData;
        }

        if (!empty($this->meta)) {
            $arrayData += ["meta" => $this->meta];
        }

        $jsonTools = new JsonTools();
        $arrayData = $jsonTools->CleanArray($arrayData);

        return json_encode($arrayData);
    }

    private function buildRefundJson()
    {
        if (!empty($this->refundAmount)) {
            return json_encode(["amount" => $this->refundAmount]);
        }
        return "";
    }

    public function get()
    {
        $this->action = "get";
        return $this;
    }
    
    public function refund($chargeId, $amount = null)
    {
        $this->action = "refund";
        $this->chargeId = $chargeId;
        $this->refundAmount = $amount;
        return $this;
    }
    
    public function archive($chargeId)
    {
        $this->action = "archive";
        $this->chargeId = $chargeId;
        return $this;
    }
    
    public function withChargeId($chargeId)
    {
        $this->chargeId = $chargeId;
        return $this;
    }
    
    public function withParameters($filter)
    {
        $this->chargeFilter = $filter;
        return $this;
    }
    
    private function buildJson()
    {
        switch ($this->action) {
            case "create":
                return $this->buildCreateJson();
            case "refund":
                return $this->buildRefundJson();
        }

        return "";
    }

    private function buildUrl()
    {
        $urlTools = new UrlTools();
        switch ($this->action) {
            case "get":
                return $urlTools->BuildQueryStringUrl("charges", $this->chargeId, $this->chargeFilter);
            case "refund":
                return "charges/" . urlencode($this->chargeId) . "/refunds";
            case "archive":
                return "charges/" . urlencode($this->chargeId);
        }

        return "charges";
    }

    public function call()
    {
        $data = $this->buildJson();
        $url = $this->buildUrl();

        return ServiceHelper::privateApiCall($this->actionMap[$this->action], $url, $data);
    }
}
?>