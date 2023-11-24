<?php

use Civi\Api4\Webhook;
use Civi\Webhook\Config;
use CRM_Webhook_ExtensionUtil as E;

/**
 * Collection of upgrade steps.
 */
class CRM_Webhook_Upgrader extends CRM_Extension_Upgrader_Base
{
    public const DEFAULT_HOOK_NAME = 'Logger webhook';

    public const DEFAULT_HOOK_DESC = 'This webhook could be used for testing purposes. It logs the received data.';

    public const DEFAULT_HOOK_HANDLER = 'Civi\Webhook\Handler\Logger';

    public const DEFAULT_HOOK_QUERY_STRING = 'logger-hook';

    public const DEFAULT_HOOK_PROCESSOR = 'Civi\Webhook\Processor\Dummy';

    /**
     * Install process. Init database.
     *
     * @throws CRM_Core_Exception
     */
    public function install(): void
    {
        $config = new Config(E::LONG_NAME);
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
        $config = new Config(E::LONG_NAME);
        // delete current configs
        if (!$config->remove()) {
            throw new CRM_Core_Exception(E::LONG_NAME.ts(' could not remove configs from database'));
        }
    }

    /**
     * Migrate data from civicrm_webhook table to civicrm_webhook_legacy table
     *
     * @return bool
     * @throws \Civi\RcBase\Exception\DataBaseException
     */
    public function upgrade_3010(): bool
    {
        $sql = "CREATE TABLE IF NOT EXISTS `civicrm_webhook_legacy` (
                  `id` int unsigned NOT NULL AUTO_INCREMENT COMMENT 'Unique Webhook ID',
                  `name` varchar(255) NULL DEFAULT NULL COMMENT 'Webhook name',
                  `description` text NULL DEFAULT NULL COMMENT 'Webhook description',
                  `handler` varchar(255) NULL DEFAULT NULL COMMENT 'Handler class',
                  `query_string` varchar(255) NULL DEFAULT NULL COMMENT 'Webhook query parameter',
                  `processor` varchar(255) NULL DEFAULT NULL COMMENT 'Processor class',
                  `options` text NULL DEFAULT NULL COMMENT 'Custom serialized data for PHP',
                  PRIMARY KEY (`id`),
                  UNIQUE INDEX `index_query`(query_string)
                )";
        \Civi\RcBase\Utils\DB::query($sql);

        $sql = 'TRUNCATE TABLE civicrm_webhook_legacy';
        \Civi\RcBase\Utils\DB::query($sql);

        $sql = 'INSERT INTO civicrm_webhook_legacy (id, name, description, handler, query_string, processor, options)
                    SELECT id, name, description, handler, query_string, processor, options
                    FROM civicrm_webhook';
        \Civi\RcBase\Utils\DB::query($sql);

        return true;
    }
}
