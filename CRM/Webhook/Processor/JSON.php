<?php

/**
 * Webhook JSON input Processor
 */
class CRM_Webhook_Processor_JSON extends CRM_Webhook_Processor_Base
{
  /**
   * Decode JSON
   *
   * @param mixed $input Input to decode
   *
   * @return mixed|null
   *
   * @throws \CRM_Core_Exception
   */
  protected function decode($input)
  {
    // Decode JSON
    $decoded = json_decode($input, true);

    // Check if successfully decoded
    if (is_null($decoded) && trim($input) != '') {
      $this->error(ts('Unable to decode JSON.'), true);
    }

    return $decoded;
  }

  /**
   * Process input
   *
   * @return mixed|null
   *
   * @throws \CRM_Core_Exception
   */
  public function input()
  {
    // Get contents from raw POST data
    $input = file_get_contents('php://input');

    // Check if valid JSON
    if (!CRM_Utils_JSON::isValidJSON($input)) {
      $this->error(ts('Not valid JSON received.'), true);
    }

    return $this->decode($input);
  }
}
