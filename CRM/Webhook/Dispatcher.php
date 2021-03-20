<?php

use CRM_Webhook_ExtensionUtil as E;

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
     */
    public function run() {
        // Get the listener param from the get object
        // listener has to be a get param.
        $listener = $_GET["listener"];
        if (empty($listener)) {
            http_response_code(400);
            echo "Missing listener.";
            exit();
        }
        // Load configs
        CRM_Core_Config::singleton();
        $config = new CRM_Webhook_Config(E::LONG_NAME);
        $config->load();
        $webhooks = $config->get()["webhooks"];
        $processorClass = "";
        $handlerClass = "";
        foreach ($webhooks as $hook) {
            if ($hook["selector"] == $listener) {
                $processorClass = $hook["processor"];
                $handlerClass = $hook["handler"];
                break;
            }
        }
        if ($processorClass == "" || $handlerClass == "") {
            http_response_code(400);
            echo "Invalid listener.";
            exit();
        }

        // Instantiate Processor & Handler
        $processor = $this->createProcessor($processorClass);
        $handler = $this->createHandler($handlerClass, $processor, []);

        // Handle request
        $handler->handle();
    }
}
