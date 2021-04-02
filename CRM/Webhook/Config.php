<?php

class CRM_Webhook_Config
{
    const DEFAULT_HOOK_NAME = "Logger webhook";
    const DEFAULT_HOOK_DESC = "This webhook could be used for testing purposes. It logs the received data.";
    const DEFAULT_HOOK_HANDLER = "CRM_Webhook_Handler_Logger";
    const DEFAULT_HOOK_SELECTOR = "logger-hook";
    const DEFAULT_HOOK_PROCESSOR = "CRM_Webhook_Processor_Dummy";

    private $configName;
    private $configuration;

    /**
     * CRM_Webhook_Config constructor.
     *
     * @param string $extensionName prefix for db.
     */
    public function __construct(string $extensionName)
    {
        $this->configName = $extensionName."_configuration";
        $this->configuration = $this->defaultConfiguration();
    }

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
     * Creates configuration table in db.
     *
     * @return bool the status of the db write process.
     */
    public function create(): bool
    {
        Civi::settings()->add([$this->configName => $this->configuration]);
        // check the save process with loading the saved content and compare
        // it with the current configuration
        $saved = Civi::settings()->get($this->configName);
        return $saved === $this->configuration;
    }

    /**
     * Removes configuration table from db.
     *
     * @return bool the status of the deletion process.
     */
    public function remove(): bool
    {
        Civi::settings()->revert($this->configName);
        // check the deletion process with loading the saved content and compare
        // it with null.
        $saved = Civi::settings()->get($this->configName);
        if (is_null($saved)) {
            // cleanup the class config
            $this->configuration = null;
            return true;
        }
        return false;
    }

    /**
     * Loads configuration from db.
     *
     * @throws CRM_Core_Exception.
     */
    public function load(): void
    {
        $conf = Civi::settings()->get($this->configName);
        // if not loaded well, it throws exception.
        if (is_null($conf) || !is_array($conf)) {
            throw new CRM_Core_Exception($this->configName." config invalid.");
        }
        $this->configuration = $conf;
    }

    /**
     * Updates the configuration in db.
     *
     * @param array $newConfig the updated config to save
     *
     * @return bool the status of the update process.
     */
    public function update(array $newConfig): bool
    {
        Civi::settings()->set($this->configName, $newConfig);
        // check the save process with loading the saved content and compare
        // it with the newConfig configuration
        $saved = Civi::settings()->get($this->configName);
        if ($saved === $newConfig) {
            $this->configuration = $newConfig;
            return true;
        }
        return false;
    }

    /**
     * Returns the configuration.
     *
     * @return array the configuration.
     *
     * @throws CRM_Core_Exception.
     */
    public function get(): array
    {
        if (is_null($this->configuration)) {
            throw new CRM_Core_Exception($this->configName." config is missing.");
        }
        return $this->configuration;
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
