<?php

use Civi\API\Exception\NotImplementedException;

/**
 * Main webhook Dispatcher
 */
class CRM_Webhook_Dispatcher {
    /**
     * Instantiate Processor
     *
     * @param string $processor_class Processor class name
     *
     * @return \CRM_Webhook_Processor_Base
     */
    protected function createProcessor(string $processor_class) {
        return new $processor_class();
    }

    /**
     * Instantiate Handler
     *
     * @param string $handler_class Handler class name
     *
     * @param \CRM_Webhook_Processor_Base $processor Webhook Processor
     *
     * @return \CRM_Webhook_Handler_Base
     */
    protected function createHandler(string $handler_class, CRM_Webhook_Processor_Base $processor, array $options = []) {
        return new $handler_class($processor, $options);
    }

    /**
     * Run Controller
     *
     * @throws \Civi\API\Exception\NotImplementedException
     */
    public function run() {
        // Load configs
        CRM_Core_Config::singleton();

        // Instantiate Processor & Handler
        $processor = $this->createProcessor(CRM_Webhook_Processor_Dummy::class);
        $handler = $this->createHandler(CRM_Webhook_Handler_Logger::class, $processor, []);

        // Handle request
        $handler->handle();
    }
}
