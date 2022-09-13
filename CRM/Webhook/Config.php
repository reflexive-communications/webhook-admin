<?php

class CRM_Webhook_Config extends CRM_RcBase_Config
{
    /**
     * Provides a default configuration object.
     *
     * @return array the default configuration object.
     */
    public function defaultConfiguration(): array
    {
        return [
            "logs" => [],
        ];
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
