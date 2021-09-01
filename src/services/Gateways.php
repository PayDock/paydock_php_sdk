<?php
namespace Paydock\Sdk\services;

use Paydock\Sdk\tools\JsonTools;
use Paydock\Sdk\tools\UrlTools;
use Paydock\Sdk\tools\ServiceHelper;

/*
 * This file is part of the Paydock.Sdk package.
 *
 * (c) Paydock
 *
 * For the full copyright and license information, please view
 * the LICENSE file which was distributed with this source code.
 */
final class Gateways
{
    private $parameters = array();
    private $action;
    private $gatewayId;
    private $actionMap = ["create" => "POST", "update" => "PUT", "delete" => "DELETE", "get" => "GET"];

    public function create($type, $name, $username, $password, $merchant = "", $mode = "")
    {
        $this->parameters = ["type" => $type, "name" => $name, "username" => $username, "password" => $password, "mode" => $mode, "merchant" => $merchant];
        $this->action = "create";
        return $this;
    }

    public function update($id, $type, $name, $username, $password, $merchant = "", $mode = "")
    {
        $this->gatewayId = $id;
        $this->parameters = ["type" => $type, "name" => $name, "username" => $username, "password" => $password, "mode" => $mode, "merchant" => $merchant];
        $this->action = "update";
        return $this;
    }

    public function delete($id)
    {
        $this->gatewayId = $id;
        $this->action = "delete";
        return $this;
    }

    public function get()
    {
        $this->action = "get";
        return $this;
    }

    public function withId($id)
    {
        $this->gatewayId = $id;
        return $this;
    }

    private function buildJson()
    {
        $jsonTools = new JsonTools();
        $arrayData = $jsonTools->CleanArray($this->parameters);

        return json_encode($arrayData);
    }

    private function buildUrl()
    {
        $urlTools = new UrlTools();
        switch ($this->action) {
            case "delete":
            case "update":
                return "gateways/" . urlencode($this->gatewayId);
            case "get":
                return "gateways" . (empty($this->gatewayId) ? "" : "/" . urlencode($this->gatewayId));
        }

        return "gateways";
    }

    public function call()
    {
        $data = $this->buildJson();
        $url = $this->buildUrl();

        return ServiceHelper::privateApiCall($this->actionMap[$this->action], $url, $data);
    }
}
