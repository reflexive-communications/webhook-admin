<?php

namespace Civi\Webhook\Handler;

use Civi\Webhook\Config;
use CRM_Webhook_ExtensionUtil as E;

/**
 * Webhook Handler for Logger
 */
class Logger extends Base
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
        $config = new Config(E::LONG_NAME);
        $config->insertLog($this->data);
    }
}
