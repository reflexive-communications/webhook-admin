<?php

use CRM_Webhook_ExtensionUtil as E;

/**
 * Form controller class
 *
 * @see https://docs.civicrm.org/dev/en/latest/framework/quickform/
 */
class CRM_Webhook_Form_WebhookSettings extends CRM_Core_Form {

    /**
     * Configdb
     *
     * @var CRM_Civalpa_Config
     */
    private $config;

    /**
     * Preprocess form
     *
     * @throws CRM_Core_Exception
     */
    public function preProcess()
    {
        // Get current settings
        $this->config = new CRM_Webhook_Config(E::LONG_NAME);
        $this->config->load();
    }

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
    }

    public function postProcess() {
        parent::postProcess();
    }
}
