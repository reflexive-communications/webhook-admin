<?php

use Civi\Api4\Webhook;
use CRM_Webhook_ExtensionUtil as E;

/**
 * Collection of upgrade steps.
 */
class CRM_Webhook_Upgrader extends CRM_Extension_Upgrader_Base
{
    const DEFAULT_HOOK_NAME = 'Logger webhook';

    const DEFAULT_HOOK_DESC = 'This webhook could be used for testing purposes. It logs the received data.';

    const DEFAULT_HOOK_HANDLER = 'CRM_Webhook_Handler_Logger';

    const DEFAULT_HOOK_QUERY_STRING = 'logger-hook';

    const DEFAULT_HOOK_PROCESSOR = 'CRM_Webhook_Processor_Dummy';

    /**
     * Install process. Init database.
     *
     * @throws CRM_Core_Exception
     */
    public function install(): void
    {
        $config = new CRM_Webhook_Config(E::LONG_NAME);
        // Create default configs
        if (!$config->create()) {
            throw new CRM_Core_Exception(E::LONG_NAME.ts(' could not create configs in database'));
        }
    }

    /**
     * Work with entities usually not available during the install step.
     * This method can be used for any post-install tasks. For example, if a step
     * of your installation depends on accessing an entity that is itself
     * created during the installation (e.g., a setting or a managed entity), do
     * so here to avoid order of operation problems.
     *
     * @throws CRM_Core_Exception
     */
    public function postInstall(): void
    {
        Webhook::create(false)
            ->addValue('name', self::DEFAULT_HOOK_NAME)
            ->addValue('description', self::DEFAULT_HOOK_DESC)
            ->addValue('handler', self::DEFAULT_HOOK_HANDLER)
            ->addValue('query_string', self::DEFAULT_HOOK_QUERY_STRING)
            ->addValue('processor', self::DEFAULT_HOOK_PROCESSOR)
            ->execute();
    }

    /**
     * Uninstall process. Clean database.
     *
     * @throws CRM_Core_Exception
     */
    public function uninstall(): void
    {
        $config = new CRM_Webhook_Config(E::LONG_NAME);
        // delete current configs
        if (!$config->remove()) {
            throw new CRM_Core_Exception(E::LONG_NAME.ts(' could not remove configs from database'));
        }
    }
}
