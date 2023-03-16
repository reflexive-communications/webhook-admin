<?php

use Psr\Log\LogLevel;

/**
 * Base Input Processor Class
 */
abstract class CRM_Webhook_Processor_Base
{
    /**
     * Process input
     *
     * @return mixed
     */
    abstract public function input();

    /**
     * Return output to client in JSON format
     *
     * @param mixed $result Result to output
     */
    public function output($result): void
    {
        CRM_Utils_JSON::output($result);
    }

    /**
     * Log and optionally return error message to client then exit
     *
     * @param mixed $message Error message
     * @param bool $output Should we output error message
     */
    public function error($message, bool $output = false): void
    {
        // Log error
        CRM_Core_Error::debug_log_message($message, false, CRM_Webhook_ExtensionUtil::SHORT_NAME, LogLevel::ERROR);

        // Should we output error message?
        if ($output) {
            $this->output($message);
        }

        CRM_Utils_System::civiExit();
    }
}
