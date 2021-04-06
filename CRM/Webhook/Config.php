<?php

class CRM_Webhook_Config extends CRM_RcBase_Config
{
    const DEFAULT_HOOK_NAME = "Logger webhook";
    const DEFAULT_HOOK_DESC = "This webhook could be used for testing purposes. It logs the received data.";
    const DEFAULT_HOOK_HANDLER = "CRM_Webhook_Handler_Logger";
    const DEFAULT_HOOK_SELECTOR = "logger-hook";
    const DEFAULT_HOOK_PROCESSOR = "CRM_Webhook_Processor_Dummy";

    /**
     * Provides a default configuration object.
     *
     * @return array the default configuration object.
     */
    public function defaultConfiguration(): array
    {
        return [
            "sequence" => 1,
            "logs" => [],
            "webhooks" => [
                0 => [
                    "name" => self::DEFAULT_HOOK_NAME,
                    "description" => self::DEFAULT_HOOK_DESC,
                    "handler" => self::DEFAULT_HOOK_HANDLER,
                    "selector" => self::DEFAULT_HOOK_SELECTOR,
                    "processor" => self::DEFAULT_HOOK_PROCESSOR,
                    "options" => [],
                    "id" => 0,
                ],
            ],
        ];
    }

    /**
     * Inserts a new webhook.
     *
     * @param array $webhook the data to save
     *
     * @return bool the status of the insert process.
     *
     * @throws CRM_Core_Exception.
     */
    public function addWebhook(array $webhook): bool
    {
        // load latest config
        parent::load();
        $configuration = parent::get();
        // duplication check - in case of duplication throws exception
        foreach ($configuration["webhooks"] as $hook) {
            if ($hook["selector"] == $webhook["selector"]) {
                throw new CRM_Core_Exception($webhook["selector"]." selector is duplicated.");
            }
        }
        // get the id and increment the global one
        $id = $configuration["sequence"];
        $configuration["sequence"] += 1;
        // save hook under the id.
        $webhook["id"] = $id;
        $configuration["webhooks"][$id] = $webhook;
        return parent::update($configuration);
    }

    /**
     * Updates an existing webhook.
     *
     * @param array $webhook the data to save
     *
     * @return bool the status of the update process.
     *
     * @throws CRM_Core_Exception.
     */
    public function updateWebhook(array $webhook): bool
    {
        // load latest config
        parent::load();
        $configuration = parent::get();
        // duplication check - in case of duplication throws exception
        foreach ($configuration["webhooks"] as $hook) {
            if ($hook["selector"] == $webhook["selector"] && $webhook["id"] != $hook["id"]) {
                throw new CRM_Core_Exception($webhook["selector"]." selector is duplicated.");
            }
        }
        $configuration["webhooks"][$webhook["id"]] = $webhook;
        return parent::update($configuration);
    }

    /**
     * Deletes an existing webhook.
     *
     * @param int $webhook the id that we want to delete.
     *
     * @return bool the status of the deletion process.
     *
     * @throws CRM_Core_Exception.
     */
    public function deleteWebhook(int $webhook): bool
    {
        // load latest config
        parent::load();
        $configuration = parent::get();
        unset($configuration["webhooks"][$webhook]);
        return parent::update($configuration);
    }

    /**
     * Delete the log entries.
     *
     * @return bool the status of the deletion process.
     */
    public function deleteLogs(): bool
    {
        // load latest config
        parent::load();
        $configuration = parent::get();
        $configuration["logs"] = [];
        return parent::update($configuration);
    }

    /**
     * Inserts a new log entry.
     *
     * @param array $data the data to store.
     *
     * @return bool the status of the insertion process.
     */
    public function insertLog(array $data): bool
    {
        // load latest config
        parent::load();
        $configuration = parent::get();
        $configuration["logs"][] = [
            "data" => $data,
            "timestamp" => time(),
        ];
        return parent::update($configuration);
    }
}
