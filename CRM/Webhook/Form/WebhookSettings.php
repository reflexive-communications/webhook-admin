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

        // Export webhooks to template
        $this->assign("webhooks", $config["webhooks"]);

        $this->setTitle(ts('Webhook Settings'));

        // Add js functions. It seems the jquery $ sign and the smarty template $ sign conflicts.
        Civi::resources()->addScriptFile(E::LONG_NAME, 'js/Form/popup.js');
    }

    public function postProcess() {
        parent::postProcess();
    }
}
