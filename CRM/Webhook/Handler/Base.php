<?php

/**
 * Webhook Handler Base
 */
abstract class CRM_Webhook_Handler_Base
{
  /**
   * Webhook Processor
   *
   * @var \CRM_Webhook_Processor_Base
   */
  protected CRM_Webhook_Processor_Base $processor;

  /**
   * Request data
   *
   * @var array|null
   */
  protected ?array $data;

  /**
   * CRM_Webhook_Handler_Base constructor.
   *
   * @param \CRM_Webhook_Processor_Base $processor Webhook Processor
   */
  public function __construct(CRM_Webhook_Processor_Base $processor)
  {
    $this->processor = $processor;
  }

  /**
   * Load API key from CiviCRM
   *
   * @param string $service_name Service name
   *
   * @return mixed|null
   */
  protected function loadAPIKey(string $service_name)
  {
    return Civi::settings()->get(CRM_Webhook_Upgrader::API_KEY_SETTING_PREFIX.$service_name);
  }

  /**
   * Process input
   */
  protected function processInput()
  {
    $this->data = $this->processor->input();
  }

  /**
   * Authenticate request
   *
   * @return bool
   */
  abstract protected function authenticate(): bool;

  /**
   * Validate request data
   *
   * @return bool
   */
  abstract protected function validate(): bool;

  /**
   * Handle request
   */
  public function handle()
  {
    $this->processInput();

    $this->validate();

    $this->authenticate();
  }
}
