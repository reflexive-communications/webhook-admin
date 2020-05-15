<?php
use CRM_Webhook_ExtensionUtil as E;

class CRM_Webhook_Page_Dispatcher extends CRM_Core_Page {

  protected CRM_Webhook_Processor_Base $processor;

  protected CRM_Webhook_Handler_Base $handler;

  public function processInput(string $processor)
  {
    try {
      // Instantiate processor
      $this->processor = new $processor();

      // Return processed input
      return $this->processor->input();
    } catch (CRM_Core_Exception $ex) {
      throw new CRM_Core_Exception(ts('Not valid or missing processor'));
    }
  }

  public function handleRequest(CRM_Webhook_Processor_Base $processor, string $handler, $data=null)
  {
    try {
      // Instantiate handler
      $this->handler = new $handler($processor, $data);

      // Handle request
      return $this->handler->handle();
    } catch (CRM_Core_Exception $ex) {
      throw new CRM_Core_Exception(ts('Not valid or missing handler'));
    }
  }

  public function run()
  {
    // Load configs
    CRM_Core_Config::singleton();

    // Get parameters
    $args=func_get_args();
    $processor=$args[1]['processor'] ?? '';
    $handler=$args[1]['handler'] ?? '';

    // Process input
    $data=$this->processInput($processor);

    // Handle request
    $this->handleRequest($this->processor,$handler, $data);
  }
}
