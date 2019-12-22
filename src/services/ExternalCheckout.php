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
final class ExternalCheckout
{
    private $actionMap = array("create" => "POST");
    private $data = array();
    private $meta = array();

    public function create($mode, $gatewayId, $successRedirectUrl, $errorRedirectUrl, $redirectUrl, $meta, $description = "", $reference = "")
    {
        $this->action = "create";
        $this->data = ["mode" => $mode, "gateway_id" => $gatewayId, "success_redirect_url" => $successRedirectUrl, "error_redirect_url" => $errorRedirectUrl, "redirect_url" => $redirectUrl, "description" => $description];
        $this->meta = $meta;
        return $this;
    }

    private function buildJson()
    {
        $jsonTools = new JsonTools();

        $arrayData = $this->data;
        $arrayData += ["meta" => $this->meta];

        $arrayData = $jsonTools->CleanArray($arrayData);

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
        
        return "payment_sources/external_checkout";
    }

    public function call()
    {
        $data = $this->buildJson();
        $url = $this->buildUrl();

        return ServiceHelper::privateApiCall($this->actionMap[$this->action], $url, $data);
    }
}
?>