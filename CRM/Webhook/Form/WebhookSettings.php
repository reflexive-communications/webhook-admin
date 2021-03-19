<?php

use CRM_Webhook_ExtensionUtil as E;

/**
 * Form controller class
 *
 * @see https://docs.civicrm.org/dev/en/latest/framework/quickform/
 */
class CRM_Webhook_Form_WebhookSettings extends CRM_Webhook_Form_WebhookBase {

    public function buildQuickForm() {
        parent::buildQuickForm();

        // get the current configuration object
        $config = $this->config->get();

        // Add new route button
        $newItemForm = CRM_Utils_System::url("civicrm/admin/webhooks/form");
        $this->assign("newItemForm", $newItemForm);

        // Add actions links
        foreach ($config["webhooks"] as $id => $webhook) {
            $actions = array_sum(array_keys($this->links()));

            $config["webhooks"][$id]["actions"] = CRM_Core_Action::formLink(
                $this->links(),
                $actions,
                ["id" => $id],
                ts("more")
            );
        }
        // Export webhooks to template
        $this->assign("webhooks", $config["webhooks"]);

        $this->setTitle(ts('Webhook Settings'));

        // Add js functions. It seems the jquery $ sign and the smarty template $ sign conflicts.
        Civi::resources()->addScriptFile(E::LONG_NAME, 'js/Form/popup.js');
    }

    /**
     * Get action Links.
     *
     * @return array Action links
     */
    public function links(): array {
        return [
            CRM_Core_Action::UPDATE => [
                'name' => ts('Edit'),
                'url' => 'civicrm/webhook/form',
                'qs' => 'id=%%id%%',
                'title' => ts('Edit webhook'),
                'class' => 'crm-popup webhook-action',
            ],
            CRM_Core_Action::DELETE => [
                'name' => ts('Delete'),
                'url' => 'civicrm/webhook/delete',
                'qs' => 'id=%%id%%',
                'title' => ts('Delete webhook'),
                'class' => 'crm-popup webhook-action',
            ],
        ];
    }

    public function postProcess() {
        parent::postProcess();
    }
}
