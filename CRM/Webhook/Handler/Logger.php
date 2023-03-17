<?php

use CRM_Webhook_ExtensionUtil as E;

/**
 * Webhook Handler for Logger
 */
class CRM_Webhook_Handler_Logger extends CRM_Webhook_Handler_Base
{
    /**
     * Authenticate request
     * Logger authenticates everything.
     *
     * @return bool
     */
    protected function authenticate(): bool
    {
        return true;
    }

    /**
     * Validate request data
     *
     * @return bool
     */
    protected function validate(): bool
    {
        return true;
    }

    /**
     * Handle request
     */
    public function handle(): void
    {
        parent::handle();
        $config = new CRM_Webhook_Config(E::LONG_NAME);
        $config->insertLog($this->data);
    }
}
