<?php

use CRM_Webhook_ExtensionUtil as E;

/**
 * Collection of upgrade steps.
 */
class CRM_Webhook_Upgrader extends CRM_Webhook_Upgrader_Base
{
  /**
   * API key settings prefix
   */
  public const API_KEY_SETTING_PREFIX = E::SHORT_NAME.'_api_key_';

  /**
   * API keys
   *
   * @var array|string[]
   */
  protected array $keys = [
    CRM_Webhook_Handler_PayPal::SERVICE_NAME => 'majom',
  ];

  /**
   * Add API key
   *
   * @param string $service Service name
   * @param mixed $value API key
   */
  protected function addAPIKey(string $service, $value)
  {
    Civi::settings()->add([self::API_KEY_SETTING_PREFIX.$service => $value]);
  }

  /**
   * Change API key
   *
   * @param string $service Service name
   * @param mixed $value API key
   */
  public function changeAPIKey(string $service, $value)
  {
    Civi::settings()->set(self::API_KEY_SETTING_PREFIX.$service, $value);
  }

  /**
   * Install process
   */
  public function install()
  {
    // Loop through services & add API keys
    foreach ($this->keys as $service => $key) {
      $this->addAPIKey($service, $key);
    }
  }

  /**
   * Uninstall process
   */
  public function uninstall()
  {
    // Loop through services & revert API keys
    foreach ($this->keys as $service => $key) {
      Civi::settings()->revert(self::API_KEY_SETTING_PREFIX.$service);
    }
  }
}
