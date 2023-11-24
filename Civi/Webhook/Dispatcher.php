<?php

namespace Civi\Webhook;

use Civi\Api4\WebhookLegacy;
use Civi\Webhook\Handler\Base;
use Exception;

/**
 * Main webhook Dispatcher
 */
class Dispatcher
{
    /**
     * Instantiate Processor
     *
     * @param string $processor_class Processor class name
     *
     * @return \Civi\Webhook\Processor\Base
     */
    protected function createProcessor(string $processor_class): Processor\Base
    {
        return new $processor_class();
    }

    /**
     * Instantiate Handler
     *
     * @param string $handler_class Handler class name
     * @param \Civi\Webhook\Processor\Base $processor Webhook Processor
     * @param array $options additional options to handler
     *
     * @return \Civi\Webhook\Handler\Base
     */
    protected function createHandler(string $handler_class, Processor\Base $processor, array $options = []): Base
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
        if (!isset($_GET['listener'])) {
            http_response_code(400);
            throw new Exception('Missing listener.');
        }
        $current = WebhookLegacy::get(false)
            ->addWhere('query_string', '=', $_GET['listener'])
            ->setLimit(1)
            ->execute();
        if (count($current) === 0) {
            http_response_code(400);
            throw new Exception('Invalid listener.');
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
