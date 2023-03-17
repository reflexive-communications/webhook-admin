<?php

/**
 * Main webhook Dispatcher
 */
class CRM_Webhook_Dispatcher
{
    /**
     * Instantiate Processor
     *
     * @param string $processor_class Processor class name
     *
     * @return \CRM_Webhook_Processor_Base
     */
    protected function createProcessor(string $processor_class): CRM_Webhook_Processor_Base
    {
        return new $processor_class();
    }

    /**
     * Instantiate Handler
     *
     * @param string $handler_class Handler class name
     * @param \CRM_Webhook_Processor_Base $processor Webhook Processor
     * @param array $options additional options to handler
     *
     * @return \CRM_Webhook_Handler_Base
     */
    protected function createHandler(string $handler_class, CRM_Webhook_Processor_Base $processor, array $options = []): CRM_Webhook_Handler_Base
    {
        return new $handler_class($processor, $options);
    }

    /**
     * Run Controller
     *
     * @throws \API_Exception
     * @throws \Exception
     */
    public function run(): void
    {
        // Get the listener param from the get object
        // listener has to be a get param.
        if (!isset($_GET["listener"])) {
            http_response_code(400);
            throw new Exception("Missing listener.");
        }
        $current = \Civi\Api4\Webhook::get(false)
            ->addWhere('query_string', '=', $_GET["listener"])
            ->setLimit(1)
            ->execute();
        if (count($current) === 0) {
            http_response_code(400);
            throw new Exception("Invalid listener.");
        }
        $hook = $current->first();
        // Instantiate Processor & Handler
        $processor = $this->createProcessor($hook['processor']);
        $options = [];
        if (!is_null($hook['options'])) {
            $options = $hook['options'];
        }
        $handler = $this->createHandler($hook['handler'], $processor, $options);

        // Handle request
        $handler->handle();
    }
}
