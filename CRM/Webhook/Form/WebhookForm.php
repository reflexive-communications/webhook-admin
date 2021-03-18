<?php

use CRM_Webhook_ExtensionUtil as E;

/**
 * Form controller class
 *
 * @see https://docs.civicrm.org/dev/en/latest/framework/quickform/
 */
class CRM_Webhook_Form_WebhookForm extends CRM_Webhook_Form_WebhookBase {

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

    /**
     * Add form validation rules
     */
    public function addRules() {
        $this->addFormRule(
            ["CRM_Webhook_Form_WebhookForm", "validateSelector"],
            ['config' => $this->config,]
        );
    }

    /**
     * Validate selector
     *
     * @param array $values Submitted values
     * @param array $files Uploaded files
     * @param array $options Options to pass to function
     *
     * @return array|bool
     */
    protected function validateSelector($values, $files, $options)
    {
        $errors = [];
        // Update configuration to latest values
        $options["config"]->load();
        $config = $options["config"]->get();

        // Loop through existing webhooks for duplication checking
        foreach ($config["webhooks"] as $hook) {

            // Handle duplication
            if ($hook["selector"] == $values["selector"]) {
                $errors["selector"] = ts(
                    "The selector '%1' already set for the '%2' webhook.",
                    ["1" => $values["selector"], "2" => $hook["name"],]
                );

                return $errors;
            }
        }

        return true;
    }

    public function postProcess() {
        parent::postProcess();
    }
}
