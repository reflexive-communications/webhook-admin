<?php

/**
 * Webhook Handler for PayPal
 */
class CRM_Webhook_Handler_PayPal extends CRM_Webhook_Handler_Base
{
  /**
   * Service name
   */
  public const SERVICE_NAME = 'paypal';

  /**
   * Debugging mode
   */
  public const DEBUG = true;

  /**
   * PayPal API Key
   *
   * @var string
   */
  protected ?string $apiKey;

  /**
   * Authenticate request
   *
   * @return bool
   */
  protected function authenticate(): bool
  {
    // Load API key
    $this->apiKey = $this->loadAPIKey(self::SERVICE_NAME);

    // Check if loaded API key valid
    if (is_null($this->apiKey) || $this->apiKey === "") {
      $this->processor->error(ts('No API key found for this service.'), self::DEBUG);
    }

    if ($this->data['id'] !== $this->apiKey) {
      $this->processor->error(ts("API key not valid."), self::DEBUG);
    }

    return true;
  }

  protected function validate(): bool
  {
    return true;
  }

  public function handle()
  {
    parent::handle();
  }
}
