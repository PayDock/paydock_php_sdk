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
    private $notificationTriggerId;
    private $actionMap = array("createTemplate" => "POST", "updateTemplate" => "POST", "getTemplates" => "GET", "deleteTemplate" => "DELETE",
        "addTrigger" => "POST", "getTriggers" => "GET", "getTrigger" => "GET", "deleteTrigger" => "DELETE");
    
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

    public function addTrigger($type, $destination, $templateId, $event)
    {
        $this->parameters = ["type" => $type, "destination" => $destination, "templateId" => $templateId, "event" => $event];
        $this->action = "addTrigger";
        return $this;
    }
    
    public function getTriggers()
    {
        $this->action = "getTriggers";
        return $this;
    }
    
    public function getTrigger($id)
    {
        $this->notificationTriggerId = $id;
        $this->action = "getTrigger";
        return $this;
    }
    
    public function deleteTrigger($id)
    {
        $this->notificationTriggerId = $id;
        $this->action = "deleteTrigger";
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
            case "addTrigger":
            case "getTriggers":
                return "notifications";
            case "getTrigger":
            case "deleteTrigger":
                return "notifications/" . urlencode($this->notificationTriggerId);
        }
        
        return "";
    }

    public function call()
    {
        $data = $this->buildJson();
        $url = $this->buildUrl();

        return ServiceHelper::privateApiCall($this->actionMap[$this->action], $url, $data);
    }
}
?>