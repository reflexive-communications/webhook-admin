<?php

use Civi\API\Exception\NotImplementedException;

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
   *
   * @throws \Civi\API\Exception\NotImplementedException
   */
  public function run()
  {
    // Load configs
    CRM_Core_Config::singleton();

    // Get parameters
    $args = func_get_args();
    $processor_class = trim($args[1]['processor'] ?? '');
    $handler_class = trim($args[1]['handler'] ?? '');

    // No explicit processor defined --> AUTO-DETECT
    if ($processor_class == '') {
      switch ($_SERVER['CONTENT_TYPE']) {
        case 'application/json':
        case 'application/javascript':
          $processor_class = CRM_Webhook_Processor_JSON::class;
          break;
        case 'text/xml':
        case 'application/xml':
          $processor_class = CRM_Webhook_Processor_XML::class;
          break;
        case 'application/x-www-form-urlencoded':
          $processor_class = CRM_Webhook_Processor_UrlEncodedForm::class;
          break;
        default:
          throw new NotImplementedException(ts('Unsupported content-type.'));
      }
    }

    // Instantiate Processor & Handler
    $processor = $this->createProcessor($processor_class);
    $handler = $this->createHandler($handler_class, $processor);

    // Handle request
    $handler->handle();
  }
}
