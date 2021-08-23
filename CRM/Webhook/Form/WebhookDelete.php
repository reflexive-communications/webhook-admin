<?php

use CRM_Webhook_ExtensionUtil as E;

/**
 * Form controller class
 *
 * @see https://docs.civicrm.org/dev/en/latest/framework/quickform/
 */
class CRM_Webhook_Form_WebhookDelete extends CRM_Webhook_Form_WebhookBase
{
    public function buildQuickForm()
    {
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
        $webhook = \Civi\Api4\Webhook::get(false)
            ->addWhere('id', '=', $this->id)
            ->setLimit(1)
            ->execute()
            ->first();
        $this->assign("hookName", $webhook["name"]);
        $this->assign("hookQueryString", $webhook["query_string"]);

        $this->setTitle(ts("Webhook Delete"));
    }

    public function postProcess()
    {
        parent::postProcess();
        \Civi\Api4\Webhook::delete(false)
            ->addWhere('id', '=', $this->id)
            ->setLimit(1)
            ->execute();

        // Show success
        CRM_Core_Session::setStatus(ts("Webhook deleted"), "Webhook", "success", ["expires" => 5000,]);
    }
}
