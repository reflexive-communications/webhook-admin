<?php

use Civi\Api4\Webhook;

/**
 * Form controller class
 *
 * @see https://docs.civicrm.org/dev/en/latest/framework/quickform/
 */
class CRM_Webhook_Form_WebhookForm extends CRM_Webhook_Form_WebhookBase
{
    private $optionValues;

    private $webhook;

    /**
     * @return void
     * @throws \API_Exception
     * @throws \CRM_Core_Exception
     * @throws \Civi\API\Exception\UnauthorizedException
     */
    public function preProcess(): void
    {
        parent::preProcess();
        $this->setOptionValues();
        if (!is_null($this->id)) {
            $this->webhook = $this->getWebhook($this->id);
        }
    }

    /**
     * Returns the webhook that connects to the id
     *
     * @param int $id webhook id
     *
     * @return array|null
     * @throws \API_Exception
     * @throws \Civi\API\Exception\UnauthorizedException
     */
    public function getWebhook(int $id): ?array
    {
        return Webhook::get(false)
            ->addWhere('id', '=', $id)
            ->setLimit(1)
            ->execute()
            ->first();
    }

    /**
     * Set option values.
     * It could be extended with the hook_civicrm_webhookOptionValues hook.
     */
    public function setOptionValues(): void
    {
        $optionValues = [
            'processors' => [],
            'handlers' => [],
        ];
        // Fire hook event.
        Civi::dispatcher()->dispatch(
            'hook_civicrm_webhookOptionValues',
            Civi\Core\Event\GenericHookEvent::create([
                'options' => &$optionValues,
            ])
        );

        $optionValues['processors']['Civi\Webhook\Processor\Dummy'] = 'Dummy processor for testing';
        $optionValues['processors']['Civi\Webhook\Processor\JSON'] = 'JSON';
        $optionValues['processors']['Civi\Webhook\Processor\UrlEncodedForm'] = 'Url Encoded Form';
        $optionValues['processors']['Civi\Webhook\Processor\XML'] = 'XML';
        $optionValues['handlers']['Civi\Webhook\Handler\Logger'] = 'DB Logger';
        $this->optionValues = $optionValues;
    }

    /**
     * @return array
     */
    public function setDefaultValues(): array
    {
        // new item - no defaults
        if (is_null($this->id) || is_null($this->webhook)) {
            return [];
        }
        // Set defaults
        $this->_defaults['name'] = $this->webhook['name'];
        $this->_defaults['query_string'] = $this->webhook['query_string'];
        $this->_defaults['handler'] = $this->webhook['handler'];
        $this->_defaults['description'] = $this->webhook['description'];
        $this->_defaults['processor'] = $this->webhook['processor'];
        $this->_defaults['id'] = $this->id;

        return $this->_defaults;
    }

    /**
     * Processor + handler options
     *
     * @param string $name
     *
     * @return array
     */
    private function getOptionsFor(string $name): array
    {
        $opts = ['' => ts('- select -')];
        foreach ($this->optionValues[$name] as $k => $v) {
            $opts[$k] = $v;
        }

        return $opts;
    }

    /**
     * @return void
     * @throws \CRM_Core_Exception
     */
    public function buildQuickForm(): void
    {
        parent::buildQuickForm();

        // Add form elements
        $this->add('text', 'name', ts('Webhook Name'), [], true);
        $this->add('text', 'query_string', ts('Query String'), [], true);
        $this->add('select', 'processor', ts('Processor'), $this->getOptionsFor('processors'), true);
        $this->add('select', 'handler', ts('Handler Class'), $this->getOptionsFor('handlers'), true);
        $this->add('textarea', 'description', ts('Description'), ['rows' => '4', 'cols' => '60'], true);
        if (!is_null($this->id)) {
            $this->add('hidden', 'id');
        }
        if (!is_null($this->webhook) && !is_null($this->webhook['query_string'])) {
            $this->assign('hook_url', CRM_Core_Config::singleton()->extensionsURL.'webhook-admin/external/listener.php?listener='.$this->webhook['query_string']);
        }

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
     * @return void
     */
    public function addRules(): void
    {
        $this->addFormRule(
            ['CRM_Webhook_Form_WebhookForm', 'validateQueryString'],
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
     * @throws \API_Exception
     * @throws \Civi\API\Exception\UnauthorizedException
     */
    public function validateQueryString($values, $files, $options)
    {
        $current = Webhook::get(false)
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
            ['1' => $values['query_string'], '2' => $current[0]['name']]
        );

        return $errors;
    }

    /**
     * @return void
     * @throws \API_Exception
     * @throws \Civi\API\Exception\UnauthorizedException
     */
    public function postProcess(): void
    {
        parent::postProcess();
        if (!is_null($this->id)) {
            $upgrader = Webhook::update(false);
            $upgrader = $upgrader->addWhere('id', '=', $this->id);
        } else {
            $upgrader = Webhook::create(false);
        }
        foreach ($this->_submitValues as $k => $v) {
            $upgrader = $upgrader->addValue($k, $v);
        }
        $upgrader->execute();

        CRM_Core_Session::setStatus(ts('Webhook Saved.'), 'Webhook', 'success', ['expires' => 5000]);
    }
}
