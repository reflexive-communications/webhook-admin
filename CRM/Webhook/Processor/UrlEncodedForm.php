<?php

/**
 * Webhook URL encoded form Processor
 */
class CRM_Webhook_Processor_UrlEncodedForm extends CRM_Webhook_Processor_Base
{
  /**
   * Process input
   *
   * @return array
   */
  public function input()
  {
    $data = [];

    // Loop through $_POST data & trim
    foreach ($_POST as $key => $value) {
      $data[trim($key)] = trim($value);
    }

    return $data;
  }
}
