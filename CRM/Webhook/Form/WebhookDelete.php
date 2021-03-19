<?php

use CRM_Webhook_ExtensionUtil as E;

/**
 * Form controller class
 *
 * @see https://docs.civicrm.org/dev/en/latest/framework/quickform/
 */
class CRM_Webhook_Form_WebhookDelete extends CRM_Core_Form {

    public function buildQuickForm() {
        parent::buildQuickForm();

        $this->addButtons(
            [
                [
                    "type" => "cancel",
                    "name" => ts("Cancel"),
                ],
                [
                    "type" => "done",
                    "name" => ts("Delete"),
                    "isDefault" => true,
                ],
            ]
        );
        $hook = $this->config->get()["webhooks"][$this->id];
        $this->assign("hookName", $hook["name"]);
        $this->assign("hookSelector", $hook["selector"]);

        $this->setTitle(ts("Delete webhook"));
    }

    public function postProcess() {
        parent::postProcess();
        if (!$this->config->deleteWebhook($this->id)) {
            CRM_Core_Session::setStatus(ts("Error while updating the configuration."), "Webhook", "error");
            return;
        }

        // Show success
        CRM_Core_Session::setStatus(ts("Webhook deleted"), "Webhook", "success", ["expires" => 5000,]);
    }
}
