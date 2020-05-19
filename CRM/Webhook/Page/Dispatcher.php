<?php

/**
 * Main webhook Dispatcher
 */
class CRM_Webhook_Page_Dispatcher extends CRM_Core_Page
{
  /**
   * Instantiate Processor
   *
   * @param string $processor_class Processor class name
   *
   * @return \CRM_Webhook_Processor_Base
   */
  protected function createProcessor(string $processor_class)
  {
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
  protected function createHandler(string $handler_class, CRM_Webhook_Processor_Base $processor)
  {
    return new $handler_class($processor);
  }

  /**
   * Run Controller
   */
  public function run()
  {
    // Load configs
    CRM_Core_Config::singleton();

    // Get parameters
    $args = func_get_args();
    $processor_class = trim($args[1]['processor'] ?? '');
    $handler_class = trim($args[1]['handler'] ?? '');

    // Instantiate Processor & Handler
    $processor = $this->createProcessor($processor_class);
    $handler = $this->createHandler($handler_class, $processor);

    // Handle request
    $handler->handle();
  }
}
