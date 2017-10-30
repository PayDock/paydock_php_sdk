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
final class Notifications
{
    private $parameters = array();
    private $action;
    private $notificationTemplateId;
    private $actionMap = array("createTemplate" => "POST", "updateTemplate" => "POST", "getTemplates" => "GET", "deleteTemplate" => "DELETE");
    
    public function createTemplate($body, $label, $notificationEvent, $html = "")
    {
        $this->parameters = ["body" => $body, "label" => $label, "notification_event" => $notificationEvent, "html" => $html];
        $this->action = "createTemplate";
        return $this;
    }
    
    public function updateTemplate($id, $body, $label, $notificationEvent, $html = "")
    {
        $this->notificationTemplateId = $id;
        $this->parameters = ["body" => $body, "label" => $label, "notification_event" => $notificationEvent, "html" => $html];
        $this->action = "updateTemplate";
        return $this;
    }
    
    public function getTemplates()
    {
        $this->action = "getTemplates";
        return $this;
    }
    
    public function deleteTemplate($id)
    {
        $this->notificationTemplateId = $id;
        $this->action = "deleteTemplate";
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
            case "updateTemplate":
            case "deleteTemplate":
                return "notifications/templates/" . urlencode($this->notificationTemplateId);
            case "createTemplate":
            case "getTemplates":
                return "notifications/templates";
        }

        return "notifications/templates";
    }

    public function call()
    {
        $data = $this->buildJson();
        $url = $this->buildUrl();

        return ServiceHelper::privateApiCall($this->actionMap[$this->action], $url, $data);
    }
}
?>