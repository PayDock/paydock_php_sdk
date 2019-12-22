<?php
namespace Paydock\Sdk;

require_once(__DIR__ . "/../tools/ServiceHelper.php");
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
final class Vault
{
    private $vaultToken;
    private $actionMap = array("create" => "POST", "get" => "GET");
    private $paymentSourceData = array();
    
    public function create($cardNumber, $expireYear, $expireMonth, $cardHolderName)
    {
        $this->action = "create";
        $this->paymentSourceData = ["card_number" => $cardNumber, "expire_month" => $expireMonth, "expire_year" => $expireYear, "card_name" => $cardHolderName];
        return $this;
    }

    public function get()
    {
        $this->action = "get";
        return $this;
    }

    public function withToken($vaultToken)
    {
        $this->vaultToken = $vaultToken;
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
        
        switch ($this->action) {
            case "get":
                return $urlTools->BuildQueryStringUrl("vault/payment_sources", $this->vaultToken, null);
        }
        
        return "vault/payment_sources";
    }

    public function call()
    {
        $data = $this->buildJson();
        $url = $this->buildUrl();

        return ServiceHelper::privateApiCall($this->actionMap[$this->action], $url, $data);
    }
}
?>