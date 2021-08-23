<?php

use CRM_Webhook_ExtensionUtil as E;

/**
 * Form controller class
 *
 * @see https://docs.civicrm.org/dev/en/latest/framework/quickform/
 */
class CRM_Webhook_Form_WebhookForm extends CRM_Webhook_Form_WebhookBase
{
    private $optionValues;
    /**
     * Preprocess form
     *
     * @throws CRM_Core_Exception
     */
    public function preProcess()
    {
        parent::preProcess();
        $this->setOptionValues();
    }

    /**
     * Returns the webhook that connects to the id
     *
     * @param int $id webhook id
     *
     * @return array
     */
    public function getWebhook(int $id)
    {
        return \Civi\Api4\Webhook::get(false)
            ->addWhere('id', '=', $id)
            ->setLimit(1)
            ->execute()
            ->first();
    }

    /**
     * Set option values.
     * It could be extended with the hook_civicrm_webhookOptionValues hook.
     */
    public function setOptionValues()
    {
        $optionValues = [
            "processors" => [],
            "handlers" => [],
        ];
        // Fire hook event.
        Civi::dispatcher()->dispatch(
            "hook_civicrm_webhookOptionValues",
            Civi\Core\Event\GenericHookEvent::create([
                "options" => &$optionValues,
            ])
        );

        $optionValues["processors"]["CRM_Webhook_Processor_Dummy"] = "Dummy processor for testing";
        $optionValues["processors"]["CRM_Webhook_Processor_JSON"] = "JSON";
        $optionValues["processors"]["CRM_Webhook_Processor_UrlEncodedForm"] = "Url Encoded Form";
        $optionValues["processors"]["CRM_Webhook_Processor_XML"] = "XML";
        $optionValues["handlers"]["CRM_Webhook_Handler_Logger"] = "DB Logger";
        $this->optionValues = $optionValues;
    }

    /**
     * Set default values
     *
     * @return array
     */
    public function setDefaultValues()
    {
        // new item - no defaults
        if (is_null($this->id)) {
            return [];
        }
        $webhook = $this->getWebhook($this->id);

        if (is_null($webhook)) {
            return [];
        }
        // Set defaults
        $this->_defaults["name"] = $webhook["name"];
        $this->_defaults["query_string"] = $webhook["query_string"];
        $this->_defaults["handler"] = $webhook["handler"];
        $this->_defaults["description"] = $webhook["description"];
        $this->_defaults["processor"] = $webhook["processor"];
        $this->_defaults["id"] = $this->id;

        return $this->_defaults;
    }
    /**
     * Processor + handler options
     *
     * @return array
     */
    private function getOptionsFor(string $name)
    {
        $opts = [ "" => ts("- select -") ];
        foreach ($this->optionValues[$name] as $k => $v) {
            $opts[$k] = $v;
        }
        return $opts;
    }

    public function buildQuickForm()
    {
        parent::buildQuickForm();

        // Add form elements
        $this->add("text", "name", ts("Webhook Name"), [], true);
        $this->add("text", "query_string", ts("Query String"), [], true);
        $this->add("select", "processor", ts("Processor"), $this->getOptionsFor("processors"), true);
        $this->add("select", "handler", ts("Handler Class"), $this->getOptionsFor("handlers"), true);
        $this->add("text", "description", ts("Description"), [], true);
        if (!is_null($this->id)) {
            $this->add("hidden", "id");
        }

        // Submit buttons
        $this->addButtons(
            [
                [
                    "type" => "done",
                    "name" => ts("Save"),
                    "isDefault" => true,
                ],
                [
                    "type" => "cancel",
                    "name" => ts("Cancel"),
                ],
            ]
        );
        $this->setTitle(ts("Webhook Form"));
    }

    /**
     * Add form validation rules
     */
    public function addRules()
    {
        $this->addFormRule(
            ["CRM_Webhook_Form_WebhookForm", "validateQueryString"],
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
    public function validateQueryString($values, $files, $options)
    {
        $current = \Civi\Api4\Webhook::get(false)
            ->addWhere('query_string', '=', $values['query_string'])
            ->setLimit(1)
            ->execute();
        if (count($current) === 0) {
            return true;
        }
        if (isset($values['id']) && $current[0]['id'] == $values['id']) {
            return true;
        }
        $errors['query_string'] = ts(
            "The query string '%1' already set for the '%2' webhook.",
            ['1' => $values['query_string'], '2' => $current[0]['name'],]
        );

        return $errors;
    }

    public function postProcess()
    {
        parent::postProcess();
        if (!is_null($this->id)) {
            $upgrader = \Civi\Api4\Webhook::update(false);
            $upgrader = $upgrader->addWhere('id', '=', $this->id);
        } else {
            $upgrader = \Civi\Api4\Webhook::create(false);
        }
        foreach ($this->_submitValues as $k => $v) {
            $upgrader = $upgrader->addValue($k, $v);
        }
        $upgrader->execute();

        CRM_Core_Session::setStatus(ts("Webhook Saved."), "Webhook", "success", ["expires" => 5000,]);
    }
}
