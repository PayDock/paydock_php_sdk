<?php
namespace Paydock\Sdk;

require_once(__DIR__."/../tools/serviceHelper.php");
use Paydock\Sdk\serviceHelper;
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
    private $paymentSourceData;
    private $customerData;
    private $action;
    
    public function create($amount, $currency, $description = "", $reference = "")
    {
        $this->chargeData = array("amount" => $amount, "currency"=>$currency, "description"=>$description, "reference" => $reference);
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
        $this->paymentSourceData = array("gateway_id" => $gatewayId, "card_number" => $cardNumber, "expire_month" => $expireMonth, "expire_year" => $expireYear, "card_name" => $cardHolderName, "card_ccv" => $ccv);
        return $this;
    }

    private function buildJson()
    {
        switch ($this->action) {
            case "create":
                return $this->buildCreateJson();
        }
    }

    private function buildCreateJson()
    {
        // TODO: unit test all creation paths with this
        // TODO: add validation that at least one payment option has been provided (eg token, customer etc)

        $arrayData = [
            'amount'      => $this->chargeData["amount"],
            'currency'    => $this->chargeData["currency"],
        ];

        if (!empty($this->chargeData["reference"])){
            $arrayData += ['reference'   => $this->chargeData["reference"]];
        }

        if (!empty($this->chargeData["description"])){
            $arrayData += ['description'   => $this->chargeData["description"]];
        }

        if (!empty($this->token)) {
            $arrayData += ["token" => $this->token];
        } else if (!empty($this->paymentSourceData)) {
            if (empty($this->customerData)) {
                $arrayData += ["customer" => ["payment_source" => $this->paymentSourceData]];
            } else {
                $customer = $this->customerData + ["payment_source" => $this->paymentSourceData];
                $arrayData += ["customer" => $customer];
            }
        }
        $data = json_encode($arrayData);

        return $data;
    }

    public function call()
    {
        $data = $this->buildJson();

        return ServiceHelper::privateApiCall("POST", "charges", $data);

        // TODO: handle errors
    }
}
?>