<?php

use CRM_Webhook_ExtensionUtil as E;

/**
 * Form controller class
 *
 * @see https://docs.civicrm.org/dev/en/latest/framework/quickform/
 */
class CRM_Webhook_Form_WebhookForm extends CRM_Core_Form {

    public function preProcess() {
        parent::preProcess();
    }

    public function buildQuickForm() {
        parent::buildQuickForm();

        // Add form elements
        $this->add('text', 'name', ts('Webhook Name'), [], true);
        $this->add('text', 'selector', ts('Selector'), [], true);
        $this->add('text', 'handler', ts('Handler Class'), [], true);
        $this->add('text', 'description', ts('Description'), [], true);
        $this->add('text', 'label', ts('Label'), [], true);

        // Submit buttons
        $this->addButtons(
            [
                [
                    'type' => 'done',
                    'name' => ts('Save'),
                    'isDefault' => true,
                ],
                [
                    'type' => 'cancel',
                    'name' => ts('Cancel'),
                ],
            ]
        );
        $this->setTitle(ts('Webhook Form'));
    }

    public function postProcess() {
        parent::postProcess();
    }
}
