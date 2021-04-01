<?php

use CRM_Webhook_ExtensionUtil as E;

/**
 * Form controller class
 *
 * @see https://docs.civicrm.org/dev/en/latest/framework/quickform/
 */
class CRM_Webhook_Form_WebhookBase extends CRM_Core_Form
{
    /**
     * Route ID
     *
     * @var int|null
     */
    protected $id;

    /**
     * Configdb
     *
     * @var CRM_Webhook_Config
     */
    protected $config;

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

        // Get route ID from request
        $this->id = CRM_Utils_Request::retrieve('id', 'Positive');
    }
}
