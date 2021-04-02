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
    private function defaultConfiguration(): array
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
        $this->load();
        // duplication check - in case of duplication throws exception
        foreach ($this->configuration["webhooks"] as $hook) {
            if ($hook["selector"] == $webhook["selector"]) {
                throw new CRM_Core_Exception($webhook["selector"]." selector is duplicated.");
            }
        }
        // get the id and increment the global one
        $id = $this->configuration["sequence"];
        $this->configuration["sequence"] += 1;
        // save hook under the id.
        $webhook["id"] = $id;
        $this->configuration["webhooks"][$id] = $webhook;
        return $this->update($this->configuration);
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
        $this->load();
        // duplication check - in case of duplication throws exception
        foreach ($this->configuration["webhooks"] as $hook) {
            if ($hook["selector"] == $webhook["selector"] && $webhook["id"] != $hook["id"]) {
                throw new CRM_Core_Exception($webhook["selector"]." selector is duplicated.");
            }
        }
        $this->configuration["webhooks"][$webhook["id"]] = $webhook;
        return $this->update($this->configuration);
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
        $this->load();
        unset($this->configuration["webhooks"][$webhook]);
        return $this->update($this->configuration);
    }

    /**
     * Delete the log entries.
     *
     * @return bool the status of the deletion process.
     */
    public function deleteLogs(): bool
    {
        // load latest config
        $this->load();
        $this->configuration["logs"] = [];
        return $this->update($this->configuration);
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
        $this->load();
        $this->configuration["logs"][] = [
            "data" => $data,
            "timestamp" => time(),
        ];
        return $this->update($this->configuration);
    }
}
